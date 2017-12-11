#!/usr/bin/perl
use strict;
use warnings;
use utf8;


my $num_args = $#ARGV + 1;
if ($num_args != 1) {
    die "Parametro nome del file mancante.\nUtilizzo: perl gulpease.pl <nomefiletex>\n";
}
my $filename = $ARGV[0];

open my $hd, "grep section $filename -v | grep \\begin -v | grep \\end -v | grep paragraph -v | detex|" or die "fallta esecuzione comandi bash per eliminare comandi latex.\n";
my $filecont = '';
while(my $line = <$hd>){
  $filecont .=$line;
}
die "Errore readline.\n" unless defined $filecont;
########################################
## Calcolo indice gulpease
#frasi = filecont.count('.') + filecont.count('?') + filecont.count('!') + filecont.count('.') + filecont.count(':') + filecont.count(';');
  #      parole = len(filecont.split())
  my @count = $filecont =~ m/[\.\?\!\:\;]/g;
  my $frasi = scalar @count;
my $parole = scalar(split /\s/,$filecont);
my $lettere=length($filecont)-$parole;
        my $lp=(100.0*$lettere)/$parole;
        my $fr=(100.0*$frasi)/$parole;
        my $gulpease=89.0-($lp/10.0)+(3.0*$fr);
        #return int(gulpease);
print "$gulpease\n\n";
#print $filecont;
