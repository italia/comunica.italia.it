
## Comunica.italia.it

### Setup locale

#### Dipendenze

* [Docker](https://www.docker.com)
* [Docker compose](https://docs.docker.com/compose)
* [Træfik](https://traefik.io)

#### Taefik

Il routing interno verso le immagini e la terminazione HTTPS sono gestite da `Træfik`.

#### Volume applicazione MacOs

Per velocizzare l'accesso su MacOs ai file nel volume dell'applicazione montato sui container `php` e `apache` è stato scelto di 
utilizzare il driver `nfs`, per abilitarlo eseguire lo script `file-setup_native_nfs_docker_osx.sh`

#### Installazione

1. copiare il file `.env.example` in un nuovo file `.env` e personalizzare le voci nella sezione `LOCAL SETTINGS`. In particolare:
   1. il path `SOURCE_DIR` deve terminare con la cartella `/html`
   2. `PROJECT_BASE_URL=comunicaitalia.loc`. Se si decide per un altro url, bisogna modificare il valore dell'impostazione `trusted_host` nei file:
      1. `/html/build/build.local.default.yml` qui modificare anche la property `domain`
      2. `/html/build/build.prod.default.yml` qui modificare anche la property `domain`
      3. `/html/web/sites/default/settings.php` qui la property si chiama `trusted_host_patterns`
2. copiare il file `docker-compose.yml.example` in un nuovo file `docker-compose.yml`
3. copiare il file `html/build/build.local.default.yml.example` in un nuovo file `html/build/build.local.default.yml`
4. modificare la configurazione di `WebProfiler` in `html/build/build.local.default.yml`. In particolare il path deve terminare 
con la cartella `/web`
5. scaricare i moduli `Monolog` e `Devel` e copiarne il contenuto nelle rispettive cartelle:
   1. `/html/web/modules/contrib/monolog`
   2. `/html/web/modules/contrib/devel`
6. spegnere eventuali servizi che potrebbero occupare le porte dello stack di questo progetto e avviare lo stack Docker con il comando `make up`
7. configurare il proprio `/etc/hosts` inserendo l'indirizzo del progetto `web.comunicaitalia.loc` o altro indirizzo come da punto 1.

#### Build

Il sistema viene ricostruito a partire dai soli file del repository GIT mediante un processo di build:

1. spostarsi nella cartella `html` ed eseguire il comando `source .aliases`
2. eseguire il comando `build` e attendere il completamento della procedura
3. il sito include un sistema di Basic Authentication, configurato in `/html/web/sites/default/settings.php` per evitare l'indicizzazione da parte dei bot.
   Username e password sono indicate nel file stesso.

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
`wellnetimages/wodby-drupal-php` dentro alla quale l'utente `wodby` è mappato sull'utente 1001 dell'host.

##### Errore 404

Potrebbero:
* non essere corretta la configurazione dell'url del progetto. Controllare i valori nei file del punto 1.2.
* non essere stata flushata correttamente la cache del dns

##### Errore "Class not found"

Dallo stack trace risalire al modulo che la contiene, verificare che esista il contenuto come da punto 5. Infine rilanciare la build.

##### Errore di Basic Authentication: username o password non corretti

Verificare i valori di `$username` e `$password` del punto 3 della fase di build.

##### Errori di drush

Messaggi relativi a moduli non installati o non abilitati indicano che la build non è andata a buon fine.

Es:
```
The service "cache_tags.invalidator.checksum" has a dependency on a non-existent service "redis.factory".
```

##### Errore "Host name not valid" nel browser

Se il browser non prompta la basic authentication, ma invece segnala il seguente errore:
```
Error: "The provided host name is not valid for this server."
```
Allora non è corretta la configurazione dell'URL di progetto al punto 1.2.

In particolare questo errore viene lanciato dal fallimento del controllo `trusted_host_patterns` in `settings.php`
