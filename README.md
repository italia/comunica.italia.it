## Comunica.italia.it

### Setup locale

#### Dipendenze

* [Docker](https://www.docker.com)
* [Docker compose](https://docs.docker.com/compose)
* [Træfik](https://traefik.io)

#### Volume applicazione MacOs

Per velocizzare l'accesso su MacOs ai file nel volume dell'applicazione montato sui container `php` e `apache` si può
utilizzare il driver `nfs`, per abilitarlo prima di tutto eseguire lo script `file-setup_native_nfs_docker_osx.sh` e
decommentare le righe relative al volume nel file `docker-compose.yml`:

* la sezione `volumes` globale
* la sezione `volumes` dei container `php` e `apache`

#### Installazione

1. copiare il file `.env.example` in un nuovo file `.env` e personalizzare le voci nella sezione `LOCAL SETTINGS`
2. copiare il file `docker-compose.yml.example` in un nuovo file `docker-compose.yml`
2. copiare il file `html/build/build.local.default.yml.example` in un nuovo file `html/build/build.local.default.yml`
3. aggiungere i seguenti path al file `/etc/hosts`:
    * 127.0.0.1 web.comunicaitalia.loc
    * 127.0.0.1 mailhog.comunicaitalia.loc
4. avviare lo stack Docker con il comando `make up`

#### Build

Il sistema viene ricostruito a partire dai soli file del repository GIT mediante un processo di build:

1. spostarsi nella cartella `html` ed eseguire il comando `source .aliases`
2. eseguire il comando `build` e attendere il completamento della procedura

#### Træfik

Il routing interno verso le immagini e la terminazione HTTPS sono gestite da `Træfik`.
Se si ha già un'istanza di Træfik in esecuzione è possibile utilizzarla direttamente impostando il nome corretto della
network esterna nel file docker-compose.yml.
Altrimenti è possibile lanciare una nuova copia di Træfik decommentando il container `traefik` nel file
`docker-compose.yml`. In questo caso è necessario prima creare un certificato HTTPS:

1. `cd certs`
2. `openssl genrsa 1024 > traefik.key`
3. `chmod 400 traefik.key`
4. `openssl req -new -x509 -nodes -sha1 -days 365 -key traefik.key -out traefik.cert`

#### Tools

Diversi comandi sono mappati in automatico sul container Docker php, quindi per usarli è sufficiente spostarsi nella
cartella `html` ed eseguire il comando `source .aliases`:

1. drush
2. drupal (Drupal Console)
3. composer
4. robo

#### Robo

Sono stati definiti una serie di task Robo per automatizzare alcune operazioni:

* `robo configuration:export -e local` esporta le configurazioni sul filesystem
* `robo configuration:import -e local` importa le configurazioni dal filesystem
* `robo content:export -e local` esporta i nodi sul filesystem

#### Risoluzione dei problemi

##### Utente 1001 su Linux

Su alcuni sistemi Linux il primo utente non root non è 1000 ma 1001. Le immagini di Wodby utilizzate in questo progetto
prevedono che l'utente sul sistema host abbia UID e GUID impostati a 1000 (lo puoi scoprire con i comandi `id -u` e
`id -g`). In questo caso è necessario sostituire l'immagine di default `wodby/drupal-php` con una custom
`wellnetimages/wodby-drupal-php:7.1-dev` dentro alla quale l'utente `wodby` è mappato sull'utente 1001 dell'host. 
