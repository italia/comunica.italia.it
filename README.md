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

1) Copiare il file `.env.example` in un nuovo file `.env` e personalizzare le voci nella sezione `LOCAL SETTINGS`
2) Copiare il file `docker-compose.yml.example` in un nuovo file `docker-compose.yml`
3) Aggiungere i seguenti path al file `/etc/hosts`:
    * 127.0.0.1 web.comunicaitalia.loc
    * 127.0.0.1 mailhog.comunicaitalia.loc
4) Avviare lo stack Docker con il comando `make up`

#### Build


