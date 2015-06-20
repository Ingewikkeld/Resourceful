ResourcefulBundle
=================

The Resourceful Bundle is a layer on top of the Friends of Symfony Rest Bundle that enriches Symfony's Routing system
and Content Negotiation, and adds support for Commands and Projections.

Theory of Operation
-------------------

The theory behind Resourceful is that with a REST API the URI determines the actions and representation of the API. As
such the Routing of Symfony is expanded to allow you to set the following:

- What actions are executed using Commands (for example with a PUT, POST, PATCH or DELETE)
- Which representation of your data is shown using Projections
- Which mime-types are accepted, a mime-type can also be used to influence which projection is used

Basic usage
-----------

Resourceful is designed reduce the amount of boilerplate code that you have to write yourself. In order to do this
it offers a Generic Controller defined as a service with an Action named ``handle`` (refered to as
``resourceful.controller:handleAction``). This controller does little more than invoking a Command if it is provided or
making a projection to return your desired resource representation.

Defining your endpoint
++++++++++++++++++++++

Writing commands
++++++++++++++++

Using Projections
+++++++++++++++++
