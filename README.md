MarkovTweeter
=============

PHP app that will generate (and post) tweets using Markov Chains.

To use copy config/config.yml.dist to config.yml and enter the Twitter API details for the account you want to post on. 
You can also enter a list of starter words that can be used to get the chain going.

To generate and post a tweet run this command:

`php app/tweet /path/to/source/file`
