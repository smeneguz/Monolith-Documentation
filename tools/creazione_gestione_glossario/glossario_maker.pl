#!/usr/bin/perl

use strict;
use warnings;
#use XML::LibXML;

#use XML::LibXSLT;
use utf8;
use Text::MultiMarkdown 'markdown';

# uso: res = markdown(arg)
# use File::Copy 'mv';
# per fare mv(origine, destinazione);
binmode STDOUT, ":utf8";
binmode STDERR, ":utf8";
binmode STDIN,  ":utf8";
use 5.12.0;

# trim both ends
# $str =~ s/^\s+|\s+$//g


die "parametri non corretti" unless $#ARGV == 0;
my $markdown_file = $ARGV[0];
print "Caricamento del file \"$markdown_file\".\n";

my %dizionario = ();

my $string = '';

{
  local $/ = undef;
  open( my $fh, "<:encoding(UTF-8)", $markdown_file )
      or die("Errore nell'apertura del file in lettura:  $!");
  $string = <$fh>;
  close $fh;
  #$string = sostituzioni_utili($string);
}

$string = markdown($string);
$string = sostituzioni_utili($string);
# tolgo spazi e \n brutti. tolgo dl a fine e inizio
$string =~ s/<dl>//g;
$string =~ s/<\/dl>//g;
$string =~ s/>[\r\n]</></g;
$string =~ s/[\r\n]/\ /g;
$string =~ s/<\/dd>\ +<dt>/<\/dd><dt>/g;
$string =~ s/^\s+|\s+$//g;
#print "\n\n$string\n\n";
my @els = split '</dd>', $string;
foreach my $el (@els){
  $el =~ s/<dt>//;
  if ($el !~ /<\/dt><dd>/){
    die "è presente più di una definizione o nessuna. operazione non permessa.\n <<$el>>\n"
  }
  my @pieces = split '</dt><dd>', $el;
  #print $pieces[0]."--->". $pieces[1]."\n"
  my $dt = $pieces[0]; $dt =~ s/^\s+|\s+$//g;
  my $dd = $pieces[1]; $dd =~ s/^\s+|\s+$//g;
  # controllo se già presente
  foreach my $k ( keys %dizionario ) {
      if ( uc $dt eq uc $k ) {
          die("$dt già presente.\n\n");
      }
  }
  $dizionario{$dt} = $dd;
}

#######################################################

open( my $out, ">:encoding(UTF-8)", 'listaTermini.txt' );
open( my $gltex, ">:encoding(UTF-8)", 'contenuto.tex' );
my $f_letter = '';
my $itemize_aperto = 'n';
my $num = 1;
foreach my $k ( sort { "\L$a" cmp "\L$b" } keys %dizionario ) {
    my $letter = substr($k,0,1);
    if(uc $letter ne uc $f_letter){
      # se cambio lettera allora controllo se ho una vecchia lettera da chiudere
      if($itemize_aperto eq 'y'){
        print $gltex '\end{itemize}'."\n".'\newpage'."\n\n";
        $itemize_aperto = 'n';
      }
        $f_letter = $letter;
        #print uc "$f_letter\n";
        print $gltex '\mysection{'.$num.'}{'.uc $f_letter."}\n";
        $num = $num +1;
        $itemize_aperto = 'y';
        print $gltex '\begin{itemize}'."\n";
    }
    #print "$k => " . $dizionario{$k} . "\n";
    print $gltex '\item[] \textbf{'.$k.'}: '. $dizionario{$k} . "\n";
    print $out "$k\n";
}
if($itemize_aperto eq 'y'){
  print $gltex '\end{itemize}'."\n";
}

close $gltex;
close $out;




#####################################################################

sub sostituzioni_utili {
my $s = shift @_;
$s =~ s/\’/\'/g;
#$s =~ s/à/\\'a/g;
#$s =~ s/è/\\'e/g;
#$s =~ s/ì/\\'i/g;
#$s =~ s/ò/\\'o/g;
$s =~ s/ù/\\'u/g;
$s =~ s/à/\\'a/g;
$s =~ s/É/\\'E/g;
return $s;
}
