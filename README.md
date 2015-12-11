Scheduler
=========

Schema
-------

* Attendees: who has been invited
	* **id**: eventId
	* **email**
	* owner: for the owner of the event contains their name, else 0
	* hash: unique for each guest, set to 0 after confirmed
* Events: final schedules of each event
	* **id**
	* <day (0-6)>_<half hour period (0-47)>

How this works
--------------

Main screen: making new events

* user fills in schedule
* functions:
	* Create event:
		* saves data under new event ID, event_name
		* generates confirmation links
		* sends e-mail to owner (confirmation link, event page)
		* Sends e-mails to guests (confirmation link, event page)
		* redirect to `/{eventId}`

Event page:

* `/{eventId}`
* Shows compiled availability of event
* free times will be green, get rid of red

Confirm link: guests fill out schedule

* `/{eventId}/{hashId}`
* `attendeeId` is really just a random hash that is saved under 
* functions:
	* Submit:
		* submits current schedule data for the event
		* Compiles new schedule
		* redirects to event page, `/{eventId}`
		* Sends e-mail to owner that person at email address has confirmed
