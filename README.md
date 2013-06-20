destructoPad
============

destructoPad is an open-source LAMP project used to create read-and-burn style encrypted messages with an expiration timer on each message. It's designed to operate inside the TOR network to prevent bad actors or (mis)users of the service from knowing where the sever is hosted, and to prevent server operators from knowing where the messages were written or read. This project was written for use on a Rapberry PI, and supports any encryption types supported in your mcrypt install supports. The default config ships with AES-256 CBC enabled.

Dependencies for the software include:
 Apache
  - with mod\_rewrite
 MySQL
 PHP 5 w/ the following:
  - mysqli module
  - mcrypt module

Configuration recommendations/requirements:
 The service is designed to be deployed inside TOR as a hidden service, so TOR being installed is a good thing.
 In order to protect the encryption keys the Apache access log for the site hosting the service should be turned off.
 Hosting the site in Apache with SSL enabled is a good idea.
