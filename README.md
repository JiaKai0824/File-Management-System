File Management System
Description
This is a File Management System that allows users to upload, view, and download files. It has a role-based authentication system with two user roles: Admin and User. The Admin has additional privileges like creating users and deleting files, while Users are restricted to viewing and downloading files.

Features:
Login System: Separate authentication for Admin and User.
File Upload: Both Admin and User can upload files to the system.
File View/Download: Users can view and download files. Admins have full control, including the ability to delete files.
File Categories: Files are categorized for easy searching and filtering.
Admin Privileges: Admin users can:
Create new users.
Delete files from the system.
User Privileges: Regular users can:
View and download files based on assigned permissions.

Technologies Used
Frontend: Vue.js (for dynamic and reactive user interfaces)
Backend: PHP (for handling requests and managing the database)
Database: MySQL (to store user, file, and permission data)
API Interaction: The application communicates with the backend through a simple RESTful API. The API allows:
Retrieving file information (GET /files).
Uploading files (POST /upload).
Managing permissions (POST /permissions).
Deleting files (DELETE /files).

How It Works
1. User Roles
Admin:
Create and manage users.
Can delete files.
View and manage file permissions.

User:
Can upload files.
Can view and download files (based on permissions).
Cannot delete files.

2. File Upload
Users can upload files to the system. Admins and users can view the list of files, which includes information about file size, name, and upload date.

3. File Permissions
File permissions are set at the file level:
View: Allows the user to view the file details.
Download: Allows the user to download the file.
Only Admin users can modify file permissions and delete files.

4. User Management
Admins can create new users with different roles. Each user is assigned specific permissions, either to view or download files, depending on their role.

Installation