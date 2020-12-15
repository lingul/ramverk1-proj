[![Build Status](https://scrutinizer-ci.com/g/lingul/ramverk1-proj/badges/build.png?b=master)](https://scrutinizer-ci.com/g/lingul/ramverk1-proj/build-status/master)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lingul/ramverk1-proj/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lingul/ramverk1-proj/?branch=master)

[![Code Coverage](https://scrutinizer-ci.com/g/lingul/ramverk1-proj/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/lingul/ramverk1-proj/?branch=master)

[![Build Status](https://scrutinizer-ci.com/g/lingul/ramverk1-proj/badges/build.png?b=master)](https://scrutinizer-ci.com/g/lingul/ramverk1-proj/build-status/master)

[![Code Intelligence Status](https://scrutinizer-ci.com/g/lingul/ramverk1-proj/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

[![CircleCI](https://circleci.com/gh/lingul/ramverk1-proj.svg?style=svg)](https://circleci.com/gh/lingul/ramverk1-proj)

# ramverk1-proj - AlltOmFilm

This is a webpage created by me, Linnea Gullmak, for the course Ramverk1 at Blekinge Institute of Technology.

## Download and install

Start off by downloading the repo, you can use the following command to clone it:
```
git clone https://github.com/lingul/ramverk1-proj

```

Proceed to update composer:
```
composer update
```

Then install the required tools:
```
make install
```

Lastly you'll need to create a mysql database to use most of the webpage, lucky i've got a bash-script to do all of this for you. This script will both create the database and the tables required.  You just need to run it:
```
bash setup-database.bash
```
