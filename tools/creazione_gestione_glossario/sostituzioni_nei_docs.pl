#!/usr/bin/perl

use strict;
use warnings;
#use XML::LibXML;

#use XML::LibXSLT;
use utf8;
#use Text::MultiMarkdown 'markdown';

# uso: res = markdown(arg)
use File::Copy 'cp';
# use File::Copy 'mv';
# per fare mv(origine, destinazione);
binmode STDOUT, ":utf8";
binmode STDERR, ":utf8";
binmode STDIN,  ":utf8";
use 5.12.0;



#########################################################################################
# anche nomi docs in corsivo
# inserire qui. NB: case insensitive

my @corsivo = ();

open( my $cc, "<:encoding(UTF-8)", 'corsivo.txt')
    or die("Errore nell'apertura del file in lettura:  $!");
while(my $linea = <$cc>){
    $linea =~ s/^\s+|\s+$//g;
    if($linea =~ m/\#/ || $linea =~ m/^\s*$/ ){next;}
    push @corsivo, $linea;
}

foreach my $c (0 .. $#corsivo){
    $corsivo[$c] =~ s/\_/\\s\+/g;
    print $corsivo[$c]."\n";
}
#########################################################################################
#CARICAMENTO FILE
# trim both ends
# $str =~ s/^\s+|\s+$//g
die "parametri non corretti" unless $#ARGV == 0;
my $file = $ARGV[0];
my $contenuto = '';
print "Caricamento del file \"$file\".\n";
{
    local $/ = undef;
    open( my $fh, "<:encoding(UTF-8)", $file )
        or die("Errore nell'apertura del file in lettura:  $!");
    $contenuto = <$fh>;
    close $fh;
}
print "Creazione della copia di backup del file: $file.back.\n";
cp($file,$file.'.back');
#########################################################################################
# sostituzioni speciali
$contenuto =~ s/\:\}/\}\:/g;

#########################################################################################
## CORSIVO
foreach my $t (@corsivo){
    #$t =~ s/^\s+|\s+$//g;
    $contenuto =~ s/($t)(?!\})/\ \\emph\{$1\}\ /gi;
}

#########################################################################################
### GLOSSARIO
open( my $fh, "<:encoding(UTF-8)", 'listaTermini.txt' )
    or die("Errore nell'apertura del file in lettura:  $!");
while (my $s = <$fh>){
    $s =~ s/^\s+|\s+$//g;
    if($contenuto !~ m/\\glossario\{$s\}/i){ # se non è gia stata fatta
        $contenuto =~ s/\ ($s)\ (?![\w\ ]*\})/\ \\glossario\{$1\}\ /i;
    }
}
close $fh;

open( my $tex, ">:encoding(UTF-8)", $file ) or die("Errore nell'apertura del file in scrittura:  $!");
print $tex $contenuto;
close $tex;
#########################################
## adesso lo ripasso e tolgo i comandi eventualmente inseriti in titoli
## questo va fatto linea per linea per semplificare la faccenda

my $contenuto2 = '';
open( my $fh2, "<:encoding(UTF-8)", $file )
    or die("Errore nell'apertura del file in lettura:  $!");
    my $num = 1;
while(my $l = <$fh2>){
  $l =~ s/^\s+|\s+$//g;
  if($l =~ m/^\\(section|subsection|subsubsection|paragraph|subparagraph)\{/){
    print "$num    $1\n";
    my $tipologia = $1;
    $l =~ s/^\\(section|subsection|subsubsection|paragraph|subparagraph)\{//;
    $l =~ s/\}$//;
    #print "yes\n" if $l =~ m/\\\w+\{[\w\d\s]+\}/;
    if($l =~ m/\\\w+\{[\w\d\s]+\}/){
      $l =~ s/\\\w+\{([\w\d\s]+)\}/$1/;

      $l = "\\$tipologia\{$l\}";
    }

  }
  $contenuto2 .= "$l\n";
  $num = $num + 1;
}
close $fh2;

open( my $tex2, ">:encoding(UTF-8)", $file ) or die("Errore nell'apertura del file in scrittura:  $!");
print $tex2 $contenuto;
close $tex2;
