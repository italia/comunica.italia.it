# six11architecture
The repository contains the frontend architecture used in sixeleven's projects, independently from the technical choices (ie: the use of a CMS).

## Installation

```
$ git clone git@bitbucket.org:sixeleven/six11architecture.git
$ cd six11architecture
$ npm install
```
This create the basic frontend structure.

----

## Configuration

#### Error notifications
`gulp-notify` help to control and manage gulp errors.

**Disable notifications**
You can disable `gulp-notify` by using enviroment variable `DISABLE_NOTIFIER`.

```
export DISABLE_NOTIFIER=true;
```

This will disable all methods; `notify()`, `notify.onError` and `notify.withReporter`.

**Enable notifications**
You can check if you have notifications disabled using the following command in a terminal to print all the environment variables:
```
printenv
```
If `DISABLE_NOTIFIER` is set, you can unset it using
```
unset DISABLE_NOTIFIER
```
