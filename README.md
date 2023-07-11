This project is developed using:
    - HTML
    - CSS
    - JavaScript
    - Vue.js
    - PHP
    - Laravel
    - MySQL
There are 2 type of users:
    - Guest
    - Admin
Guest is able to:
    - Follow/Unfollow other users
    - Share images (+description)
    - Like, comment and save comments of other people he/she follows
    - Search for other users ( users are show live based on input string )
    - Chat with friends ( Chat is created using Laravel Broadcasting)
    - Report posts or comments
Admin is able to:
    - Remove reported comments or posts or remove them from reported list.

Admin is created for practice, this type of user is not necessery.
Notification system is implemented using Laravel Notifications. Notifications are being sent/received in real-time.
Both users are able to reset password. They are going to receive reset link with reset code on their email adress. Code is used to prove users ownership over account. When user change password, link wont be available for second use.
