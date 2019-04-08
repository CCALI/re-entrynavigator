# re-entrynavigator
LITCon 2019 Hackathon project

This hackathon project is two-fold. We created an A2J Guided Interview for people re-entering the community after being incarcerated. It produces potentially three documents for the end user: a verification of employment form, a substance abuse recovery meetings verification form, and a "Personalized Action Plan" that contains resources for finding affordable housing, employment, and drug testing facilities depending on what the user selects as needed help. This is currently hosted on www.a2j.org for public testing and use. To run the Re-Entry Navigator A2J Guided Interview, including the ability to save your answers and come back again, please use this URL: https://www.a2j.org/guide/go/1ab639bb-30ec-4ac2-b744-440496b410c4

The second part of the hackathon project was to add the ability to A2J Guided Interviews for users to receive text messaged reminders and/or emailed calendar invitations of their upcoming parole officer check-ins. The user can opt-in to this feature, it's not automatically triggered by the interview. The email component is not finished.  Emails are sent but may be filtered out by spam filters and no calendar is currently attached, however, the text messaging part is stable. To test these new features out, please use this URL: https://hackdev.a2jauthor.org/a2j-viewer/viewer/viewer.html?templateURL=../guides/5caa68a4031f0-hackathon-2019-reentry-navigator-a2j-guided-interview-4/Guide.xml&fileDataURL=../guides/5caa68a4031f0-hackathon-2019-reentry-navigator-a2j-guided-interview-4/&setDataURL=./hackathon-sms.php

If you are interested in working on this A2J Guided Interview for your own project, you can download the zipped interview file and upload it to your own account on www.a2jauthor.org (under the Author tab is where you create an "Authoring" account and where you can log in to the software once you have an account). If you have any issues creating an account or accessing your account, reach out to webmaster@a2jauthor.org

To host this A2J Guided Interview, you will need the A2J Viewer and the A2J Document Assembly Tool. These are both available for free to anyone creating projects for self-represented litigants. You can find the A2J Viewer Github Repo here:https://github.com/CCALI/a2jviewer You can find the A2J Document Assembly Github Repo here: https://github.com/CCALI/a2jdat

To host the sms and email code you will need a twilio account, functioning mail server, and php. Follow these steps:

1.) setup the viewer and dat as described in the instructions above

2.) place hackathon-sms.php and ics.php in the viewer.html directory

3.) extract the text-demo.zip file to a library directory of your choosing. Note the location

4.) create a config.ini with the appropriate keys in a place that is not web accessible as this is where sensitive data will
be stored. Note the location. A sample config.ini is provided.

5.) modify line 8 to point to your config.ini

6.) add the lib_dir key to point to the location of the extracted text-demo.zip

7.) unzip the guided interview to the guides folder using a zip extraction utility or the demo tool located in /a2j-viewer/viewer/index.php

8.) rename hackathon-sms.php to answers.php to overwrite answers.php or modify viewer.html to change setDataURL to hackathon-sms.php

9.) run the A2J Guided Interview (R) with the demo uploader link
