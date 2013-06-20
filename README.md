destructoPad
============

destructoPad is an open-source LAMP browser-based project used to create read-and-burn style messages that are encrypted and have an expiration timer on each message. It's designed to operate inside the TOR network to prevent bad actors or (mis)users of the service from knowing where the sever is hosted, and to prevent server operators from knowing where the messages were written or read. This project was written for use on a Rapberry PI, and supports any encryption types supported in your mcrypt install supports. The default config ships with AES-256 CBC enabled.

Security features:
 - Unknown network endpoints (provided through TOR):
  - The server doesn't know what IP or location requests come from.
  - The requestor doesn't know where the server is located.
 - On-server encryption
  - The pads are stored in an encrypted from on the server. If logging of HTTP access requests is properly disabled messages should be relatively safe.
 - Self-destruction
  - The encrypted pads are deleted when they expire on the server. The expiration is triggered by running a script every hour.
 - Plausible deniability
  - Messages are sent over TOR which obscures the location of the sender and reciever while providing encryption in transport.
  - The expiration timeout values are somewhat randomized and do not include a timestamp that indicates when they were created. Instead a TTL-type value is used. This means it is difficult to correlate a pad's creation with a timestamp on the server using the data layer in MySQL mode.
  - If the server is properly configured the operator of the server should not be able to know what users of the service are doing.

Dependencies for the software include:
 - Apache
  - with mod\_rewrite
 - MySQL
 - PHP 5 w/ the following:
  - mysqli module
  - mcrypt module

Configuration recommendations/requirements:
- The service is designed to be deployed inside TOR as a hidden service, so TOR being installed is a good thing.
- In order to protect the encryption keys the Apache access log for the site hosting the service should be turned off.
- Hosting the site in Apache with SSL enabled is a good idea.
 
Configuration examples and some scripts are provided under the "protected" folder.

Disclaimer:
- The creators and maintainers of this code are not responsible for any harm or damages done by its use or misuse. This code is intended for lawful uses and is provided with no warranty or guarantees.
