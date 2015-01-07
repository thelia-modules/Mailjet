Mailjet for Thelia
===
author: Benjamin Perche <bperche@openstudio.fr>

Based on the APIv3 Documentation of mailjet.

0. Prerequisites

You must have a Mailjet account and have created a mailing list.
Get your mailing list address that is like ```xxxxxx@lists.mailjet.com``` and keep the 'xxxxxx' in mind. 

1. Installation

There is two ways to install the mailjet module:
- Download the zip archive of the file, import it from your backoffice or extract it in ```thelia/local/modules```
- require it with composer:
```
"require": {
    "thelia/mailjet-module": "~1.0"
}
```
    
Then go to the configuration, give your API key and secret, you can let the webservice address at its default value,
then entre the your mailing list name (the 'xxxxxx') into the 4th field.
Save and you're done !