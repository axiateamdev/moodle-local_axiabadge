# moodle-local_axiabadge
Send an email to the student's manager (PEM) when the student obtains a badge.

TASKS:
Development of a PLUGIN that will capture the event of the award of a badge, 
and send the PEM an email informing him/her of the event.
The PEM will be obtained from the user's custom fields in MOODLE, which previously 
would have been populated via data synchronization from ATENEA.
The student's PEM field contains the name and surname of the person in charge, 
the plugin will look for it in the MOODLE user table and will obtain the PEM's email.
