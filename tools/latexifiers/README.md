# Utilizzo script e database remoto

## Connessione al db tramite tunnel

### Connessione da rete interna al dipartimento

        ssh -L23306:localhost:3306 -L20443:localhost:443 -L20080:localhost:80 -L20022:localhost:22 nrigato@basidati.studenti.math.unipd.it

### Connessione da rete esterna al dipartimento
Primo terminale:

        ssh -L23306:basidati:3306 -L20443:basidati:443 -L20080:basidati:80 -L20022:basidati:22 nrigato@ssh.studenti.math.unipd.it

Secondo terminale:

        ssh -p 20022 -L33306:localhost:3306 -L30443:localhost:443 -L30080:localhost:80 -L30022:localhost:22 nrigato@localhost


### Dipendenze script

Installazione php e ssh (in ubuntu e derivate):

        apt-get install php5-cli php5-mysql openssh-client

### Configurazione preliminare
Inserire un file "configurazione.php" in "Obelix/tools/latexifiers/lib" con

+ path della cartella principale del repository nel sistema
+ username del laboratorio
+ password del db (trovata nella home in "bd2017.password")

esempio:

        <?php 
        $repoPath = '/home/nick/Documenti/INGEGNERIA/Obelix/';
        $username = "nrigato";
        $passwd = "1234567890";
        ?>

## Invocazione script

Una volta aperto il tunnel gli script vanno invocati nel seguente modo:

        php <nome_file_script>.php <rete>

dove rete può essere home oppure unipd a seconda di dove venga eseguita la procedura.

## PHPMYADMIN

Il database va popolato con phpmyadmin. Si trova all'indirizzo:

+ https://localhost:20443/phpmyadmin su rete interna
+ https://localhost:30443/phpmyadmin su rete esterna


NB: per la connessione a phpmyadmin da rete esterna è sufficiente il primo terminale
NB: con la connessione a phpmyadmin da rete esterna a me non riesce una connessione sicura. bisogna dunque accettare di accedere al sito con connessione non sicura
