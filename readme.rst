###############################
CodeIgniter Example for XML-RPC
###############################

**************
Intended Usage
**************

Fork/clone this project, or else just download it.
Play.

*****
Setup
*****

Assumed that the CI system folder is in '../system3';
if not, tailor your index.php.

*****
F.A.Q
*****

How do I debug my server-side stuff?
Client or server-side, or both, $this->xmlrpc->set_debug(true);

***************
Important Stuff
***************

controllers/Welcome The homepage, with XML-RPC client
controllers/Service The XML-RPC server
models/Airline  A simple data model accessed locally if without XML-RPC and remotely if with XML-RPC
/data           XML document with flight schedule data, to avoid using an RDB

*******
License
*******

Please see the `license agreement <http://codeigniter.com/userguide3/license.html>`_

*********
Resources
*********

-  `Informational website <http://codeigniter.com/>`_
-  `Source code repo <https://github.com/bcit-ci/CodeIgniter/>`_
-  `User Guide <http://codeigniter.com/userguide3/>`_
-  `Community Forums <https://forum.codeigniter.com/>`_
-  `Community Wiki <https://github.com/bcit-ci/CodeIgniter/wiki/>`_
-  `Community IRC <http://codeigniter.com/irc>`_

***************
Acknowledgement
***************

This project (such as it is) was written by James Parry, 
Instructor in Computer Systems Technology 
at the British Columbia Institute of Technology,
and Project Lead for CodeIgniter.

CodeIgniter is a project of B.C.I.T.
