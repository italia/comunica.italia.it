## Comunica.italia.it

### Setup locale

#### Dipendenze

* [Docker](https://www.docker.com)
* [Docker compose](https://docs.docker.com/compose)
* [Træfik](https://traefik.io)

#### Taefik

Il routing interno verso le immagini e la terminazione HTTPS sono gestite da `Træfik`.
Se si ha già un'istanza di Træfik in esecuzione è possibile utilizzarla direttamente, è sufficiente impostare il nome
corretto della network esterna nel file docker-compose.yml.
Altrimenti è possibile lanciare una nuova copia di Træfik utilizzando il file `docker-compose-traefik.yml`. 

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

#### Robo

Sono stati definiti una serie di task Robo per automatizzare alcune operazioni:

* `robo configuration:export -e local` esporta le configurazioni sul filesystem
* `robo configuration:import -e local` importa le configurazioni dal filesystem
* `robo content:export -e local` esporta i nodi sul filesystem

#### Tools

Diversi comandi sono mappati in automatico sul container Docker php, quindi per usarli è sufficiente spostarsi nella
cartella `html` ed eseguire il comando `source .aliases`:

1. drush
2. drupal (Drupal Console)
3. composer
