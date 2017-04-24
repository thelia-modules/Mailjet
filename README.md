Mailjet for Thelia
===
Authors: 
- Benjamin Perche <bperche@openstudio.fr>
- Franck Allimant <franck@cqfdev.fr>

This module keep a Mailjet contact list of your choice synchronized whith the newsletter subscriptions and unsubscriptions 
on your shop :

- When a user subscribe to your newsletter on your shop, it is automatically added to the Mailjet contact list.

- When a user unsubscribe from your list, it is also deleted from the Mailjet contact list. The user is nor deleted from Mailjet contacts, but is only removed from the contact list.

The module is based on the [APIv3 Documentation of Mailjet](https://dev.mailjet.com/email-api/v3/apikey/).

0. Prerequisites

You must have a Mailjet account and have created a contact list.
Get your mailing list address on the contact list page, which is like ```xxxxxx@lists.mailjet.com``` and take note of the 'xxxxxx' part. 

You'll also need your API Key and Secret key. You'll find them in your Mailjet account, see the REST API section. 
 
1. Installation

There is two ways to install the mailjet module:
- Download the zip archive of the file, import it from your backoffice or extract it in ```thelia/local/modules```
- require it with composer:
```
"require": {
    "thelia/mailjet-module": "~1.0"
}
```
    
Then go to the configuration panel, give your API key and secret. You can leave the webservice address as its default value. Then enter your mailing list name (the 'xxxxxx') into the 4th field.

Save and you're done !
