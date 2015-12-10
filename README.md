Scheduler
=========

Schema
-------

* Attendees: who has been invited
	* **id**
	* **email**
	* owner: owner of event
* Events: final schedules of each event
	* **id**
	* <day (0-6)>_<half hour period (0-47)>
* User: for registered users
	* **id**
	* name
	* email
* Schedule: each person's schedule
	* **id**
	* <day (0-6)>_<half hour period (0-47)>

How this works
--------------

Main screen:

* user fills in schedule with data
* functions:
	* Login (enter e-mail address): redirects to /u/{userId}
	* Save: use email and name. if e-mail already exists, update calendar with new data
	* Create event: saves data under new event ID, name, sends e-mail to guests

User page:

* `/u/{userId}`
* Loads current user's schedule data
* functions:
	* Save: updates current user's schedule

Event page:

* `/{eventId}`
* Shows compiled availability of group
* functions:
	* "Create new": if you haven't added your schedule, create a new one (sends to home page) and save it under your email and name
	* Confirm schedule: enter your e-mail address and press confirm if you already have saved your calendar. Error if entry doesn't exist.

Confirm link:

* `/confirm/{eventId}/{userId}`
* Redirects to event page