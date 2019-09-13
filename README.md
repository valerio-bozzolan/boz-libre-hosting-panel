# Boz's Libre Hosting Panel

This projects was started to create another _keep it simple and stupid_ libre web hosting control panel suitable for providers of everyday shared hosting services.

The panel is designed to **respect the freedom** of the users. In order to do it, it works **without proprietary JavaScript**. To be honest, without **any line of JavaScript** in any form.

I would like to thank Giorgio Maone for his project NoScript, for the moral incentive, and Richard Stallman, for _The JavaScript Trap_ paper.

* https://www.gnu.org/philosophy/javascript-trap.html
* https://noscript.net/

## Disclaimer

Do not try to become a system administrator if you do not like responsibilities, if you do not want to understand your infrastructure, if you do not want to have information security paranoia, etc.

## Preamble

An hosting panel is just the iceberg summit of a lot of technologies and protocols involved. Do not try to implement such project in production if you do not know what you are doing. You must gain confidence with the technologies involved.

Papers:
* RFC 5321 - Simple Mail Transfer Protocol
* RFC 7208 - Sender Policy Framework (SPF)
* RFC 6376 - DomainKeys Identified Mail (DKIM) Signatures
* RFC 7489 - Domain-based Message Authentication, Reporting, and Conformance (DMARC)

Software involved:
* Debian GNU/Linux stable
* MariaDB / MySQL
* Postfix
* Dovecot
* PureFTPd
* OpenDKIM
* Apache HTTP server / nginx
* PHP

## Features

Let me say that I love listening to the whishlist of my costumers. Here are the most important features/TODOs:

- administration of own mailboxes (thanks to Postfix and Dovecot over MariaDB)
	- [X] list
	- [X] password reset
	- [X] add
	- [X] IMAP/SMTP documentation
	- [ ] remove
	- [ ] view quota
- administration of own mail aliases (thanks to Postfix and Dovecot over MariaDB)
	- [X] list
	- [X] change forward destination(s)
	- [X] add
	- [X] remove
- administration of own FTP accounts (thanks to Pure-FTPd over MariaDB)
	- [X] list
	- [X] add
	- [X] remove
	- [ ] password reset
- [ ] administration of own MariaDB databases
- [ ] administration of User(s)
	- [X] password reset
	- [X] create a Domain for that User
	- [ ] change login
	- [ ] change e-mail
- [X] plans
	- [X] limit number of mailboxes per domain
	- [ ] limit number of mail forwardings

## License

Copyright (C) 2018 [Valerio Bozzolan](https://boz.reyboz.it/) - _Boz Libre Hosting Panel_

This program is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along with this program. If not, see <https://www.gnu.org/licenses/>.
