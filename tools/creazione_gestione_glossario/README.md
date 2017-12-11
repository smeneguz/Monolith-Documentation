# Istruzioni per gli script di generazione del glossario

## Creazione del glossario

        $perl glossario_maker.pl <nome_file>

Lo script chiede in input il nome del file che contiene i termini di glossario con le definizioni in notazione multimarkdown. [Riferimento alla guida della sintassi](https://github.com/fletcher/MultiMarkdown/wiki/MultiMarkdown-Syntax-Guide). Lo script si occupa di ordinare i termini e suddividerli per carattere iniziale.

Vengono creati i file:

+ **listaTermini.txt**, che contiene la lista dei termini di glossario per essere utilizzati dal secondo script
+ **contenuto.tex** che contiene il glossario in formato latex pronto per essere incluso in un file che contenga anche le impostazioni di compilazione (nel nostro caso **glossario.tex**)

Bug conosciuti: i caratteri speciali da MacOs rompono la compilazione latex. To be fixed.

## Modifica dei documenti per includere i rifermenti al glossario

        $perl sostituzioni_nei_docs.pl <nome_file>

Lo script chiede in input il nome di un fine **contenuto.tex** come organizzati in questo repository. Lo script crea un file di backup con estensione **.back** e poi modifica il file nei seguenti modi:

+ per ogni termine incluso in **corsivo.txt** il temine viene inserito in un tag **emph**. 
    * il file corsivo.txt deve essere formato con un termine per riga. In caso di termini di più di una parola gli spazi vanno sostituiti da underscore.
+ ogni occcorrenza di :} viene sostituita con }: 
+ i termini presenti in listaTermini.txt vengono messi in un tag **glossario**
