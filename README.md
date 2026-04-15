# Personal Task Planner.

## 1. Introduction
This project requires the design and development of a Personal Task Planner web application. The main aim of the system is to assist users in organizing, managing, and tracking their daily tasks in an efficient manner. This application enables the user to perform various tasks such as creating tasks, assigning priority levels, and tracking the tasks in a well-structured manner. This system was designed to solve the following problems: lack of organization in tasks, inability to meet deadlines, and lack of tracking of tasks that have been completed. By providing a clear interface, task list, and calendar view, the application enables the user to be productive and ensures that they are on top of their tasks. The target audience of the system includes students and people who need a simple and effective application to manage their tasks. The system was designed to be simple, efficient, and easy to use, such that the user can interact with the system without any advanced technical skills.
Key features of the system include task creation, editing, deletion, completion tracking, and a calendar that visually highlights upcoming tasks. The system also includes user authentication to ensure that each user can securely manage their own tasks.
Overall, this project demonstrates the development of a full-stack web application using PHP, MySQL, HTML, and CSS, combining both frontend design and backend functionality to deliver a complete working system.

## 2. User and System Requirements
### 2.1 User Requirements
The following user requirements were identified for the system. These describe what the end user should be able to do when interacting with the application.
- The user should be able to create new tasks with a title, description, due date, priority, and category.
- The user should be able to view all tasks in a structured list.
- The user should be able to mark tasks as completed.
- The user should be able to restore completed tasks if needed.
- The user should be able to delete tasks permanently.
- The user should be able to view tasks filtered by specific dates using a calendar interface.
- The user should be able to view tasks due on the current day.
- The user should be able to navigate easily between dashboard, tasks, and completed tasks pages.
- The user should be able to securely log in and access their own tasks only.
These requirements were prioritised based on their importance to the core functionality of the system. Task creation, viewing, and completion were considered high priority, as they are essential to the usability of the application.

### 2.2 System Requirements
The system requirements define the technical and functional components needed to support the user requirements.
- The system must use a MySQL database to store user and task data.
- The system must implement CRUD functionality (Create, Read, Update, Delete) for tasks.
- The system must use PHP for backend processing and server-side logic.
- The system must use HTML and CSS to provide a responsive and user-friendly interface.
- The system must implement session management to securely handle user authentication.
- The system must ensure that each user can only access their own data.
- The system must dynamically update task status (e.g. pending, completed).
- The system must visually display tasks on a calendar using indicators.
- The system must update the calendar dynamically when tasks are completed or deleted.
- The system must provide smooth navigation between all pages.
These requirements ensure that the system is functional, secure, and capable of handling user interactions effectively.

### 2.3 Scrum User Stories
An Agile-inspired approach was used during development, where features were built incrementally and improved through continuous testing and feedback. Scrum-style user stories were used to define the system from the user’s perspective and guide development decisions.
- As a user, I want to create a task so that I can keep track of my work.
- As a user, I want to assign a due date to tasks so that I can meet deadlines.
- As a user, I want to set task priorities so that I can focus on important tasks first.
- As a user, I want to mark tasks as completed so that I can track my progress.
- As a user, I want to delete tasks so that I can remove unnecessary items.
- As a user, I want to restore completed tasks so that I can reuse them if needed.
- As a user, I want to view tasks for a specific day so that I can plan my schedule.
- As a user, I want to see tasks displayed on a calendar so that I have a visual overview.
- As a user, I want to log in securely so that my data is protected.
- As a user, I want an easy-to-use interface so that I can manage tasks efficiently.
These user stories supported an iterative development process, where features were gradually implemented, tested, and refined. This reflects Agile principles such as continuous improvement, user-focused design, and incremental delivery.

## 3. Scrum Backlog

The following backlog was created using a Scrum-inspired approach to organise and prioritise features required for the system. Each feature was assigned a priority level and included a description and test criteria to ensure it met the project requirements.

<img width="1375" height="621" alt="image" src="https://github.com/user-attachments/assets/45a97854-69f6-4f02-910e-100041ea22cd" />

## 4. System Design

### 4.1 Overall Architecture
The system was built using a simple client-server structure. The frontend (what the user sees) was created using HTML and CSS, while PHP was used for the backend to handle all the logic and data processing.

Whenever a user performs an action, such as adding or completing a task, the request is sent to the PHP backend. The backend then processes the request and updates the MySQL database. After that, the updated information is retrieved and displayed back to the user.

This approach keeps everything organised by separating the user interface, the logic, and the data. It also makes the system easier to manage and improve in the future.

---

### 4.2 Database Design
The system uses a MySQL database to store all task-related data. The main table used is the "tasks" table, which holds all the information needed for each task.

The table includes the following fields:
- id: A unique ID for each task
- user_id: Links the task to a specific user
- title: The name of the task
- description: Extra details about the task
- due_date: The deadline for the task
- priority: Indicates importance (high, medium, low)
- category: Groups tasks (e.g. work, study, personal)
- status: Shows whether the task is pending or completed
- created_at: Records when the task was created

This structure allows the system to easily store, retrieve, and update tasks. It also supports features like filtering tasks by date and tracking completed tasks.

ERD:
<img width="1400" height="900" alt="image" src="https://github.com/user-attachments/assets/11e7de6b-23fa-4983-9380-e55468430b03" />


---

### 4.3 User Interface Design
The user interface was designed to be clean, simple, and easy to use. The aim was to make sure users can quickly understand how to use the system without any confusion.

The main pages in the system include:
- Dashboard: Shows an overview of tasks, including total tasks, completed tasks, and tasks due today
- Tasks Page: Displays all active tasks with options to complete or delete them
- Completed Tasks Page: Shows tasks that have been completed, with options to restore or remove them
- Add Task Page: Allows users to create new tasks
- Calendar: Displays tasks visually based on their due dates, including indicators for days with tasks

A sidebar navigation menu is used across all pages so users can move between sections easily. The design is consistent across the system, which improves usability and makes the overall experience smoother.

4.4 Alternative considerations:
During the design phase, various alternatives were explored before reaching a conclusion on the structure of the system. The alternatives included developing a more complex system with a JavaScript framework, which would allow for a more dynamic interface with the help of React. However, this option was ruled out as the complexity of the code would increase, which was not required for a simple task planner system.

The other alternative was to implement a full calendar system instead of a mini calendar. The full calendar would allow for a number of features, including drag-and-drop, which would provide a much better visual experience. However, this option was ruled out as the implementation of a full calendar would require a number of libraries, which would increase the complexity of the code. The other option of a mini calendar was implemented as it would provide a good visual overview, which would satisfy the requirements of the users.

During the database design phase, the option of splitting the tasks into multiple tables was explored. However, a single table structure was implemented to keep the complexity of the code simple, efficient, and easier to manage, as the complexity of the database structure was not required. 
Overall, the final design choices were made based on balancing functionality, simplicity, and development time, ensuring the system meets it's requirements without unnecessary complexity.

Screenshot 1: Dashboard:
<img width="1440" height="810" alt="image" src="https://github.com/user-attachments/assets/a8ee633f-169b-4371-bc4a-8ac9c0dbeecf" />

Screenshot 2- Tasks:
<img width="1440" height="807" alt="image" src="https://github.com/user-attachments/assets/11805ac8-8857-42bb-a8f6-287a511ffbb6" />

Screenshot 3- Completed tasks:
<img width="1440" height="809" alt="image" src="https://github.com/user-attachments/assets/f450ecd4-6202-4db5-8255-d7ba8afa2983" />

Screenshot 4- Add Tasks Form:
<img width="1440" height="810" alt="image" src="https://github.com/user-attachments/assets/9f3c4fe8-6852-4a17-bc9f-233ba2bce411" />

## 5. Implementation
The system was developed by utilizing a combination of both frontend and backend technologies. The design of the user interface/user experience of the application was achieved through the use of HTML and CSS. The main objective of the design was to ensure a clean interface. The backend of the application was developed through the use of PHP for all the logic. The logic included handling user input, session handling, and interacting with the database. The MySQL database was used for storing all task-related information, including title, description, due dates, and task status. XAMPP was used as a local environment for developing and testing the application.

This system has a number of important features, and they were developed gradually. The user can add new tasks by filling out the information regarding the title, description, due date, priority, and category. The tasks are stored in the database and displayed on the page. The user can view all their tasks in a structured list, making it easy to understand what needs to be done. The tasks can also be marked as completed, and they are displayed in the section for completed tasks. The tasks can be restored back to the main list of tasks if needed. Users can also delete tasks, and they are then permanently removed from the database.

A mini calendar feature was implemented on the dashboard to give users a visual overview of their tasks. Instead of acting as a full calendar system, it highlights specific dates that contain tasks using small indicators. Users can click on a date to view tasks scheduled for that particular day. This provides a simple but effective way to track tasks without overcomplicating the interface.

Moreover, the dashboard has a section called "Due Today" that shows tasks that are due on the current date. A navigation system has been consistently applied throughout the project, which has a sidebar that enables easy navigation from one page to another. The features were built in an incremental manner, which helped in testing and identifying bugs.


Below is an example of how a new task is added to the database using PHP:

php:
$sql = "INSERT INTO tasks (user_id, title, description, due_date, priority, category, status)
VALUES ('$user_id', '$title', '$description', '$due_date', '$priority', '$category', '$status')";

This query takes user input from the form and inserts it into the database. Session handling was also implemented to ensure that only logged-in users can access the system:

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

## 6. Project Management
The development of this project followed an Agile-inspired approach, where the system was built gradually in stages rather than all at once. Features were implemented step by step, tested, and then improved based on any issues that were identified. This allowed the project to develop in a flexible way and made it easier to fix problems early.

This process began by planning how the system would be structured in terms of its layout. After planning, the basic functionality of adding tasks and viewing tasks was implemented. After, the implementation of other features, including completing tasks, restoring tasks, and deleting tasks, was done.

As the development progressed, features like the mini calendar and the "Due Today" section were added. These features were also tested and refined to ensure they worked properly and made the system easier to use.

Throughout the project, regular testing and adjustments were made. For example, an issue was identified where completed tasks were still showing as active on the calendar. This was resolved by updating the logic to ensure only active tasks were displayed. Other improvements included fixing layout inconsistencies and improving navigation between pages.

A simplified development timeline is shown below:

<img width="1200" height="600" alt="image" src="https://github.com/user-attachments/assets/623d9030-2116-4f4e-97c1-0dea58dea76a" />


Development meeting logs: 
<img width="1600" height="900" alt="image" src="https://github.com/user-attachments/assets/58b3679d-ab9d-4ccc-ac91-8921711f63c6" />


This iterative process reflects Agile principles such as continuous improvement, incremental development, and responding to issues as they arise.

7. Tools and Techniques

This project was built using simple and practical tools. Visual Studio Code was used to write and manage the code, and XAMPP was used to run a local server and connect to the database during development and testing.

PHP was used for the backend to handle forms, sessions, and database interaction. MySQL was used to store and manage user and task data. HTML was used to structure the pages, and CSS was used to style the interface and keep it consistent across all pages.

Key techniques included CRUD operations for managing tasks, session handling for secure login, and conditional logic to control features such as task status and calendar indicators.

These tools and techniques were selected as they are appropriate for web-based system development, allowing efficient implementation, testing, and maintenance while meeting both functional and non-functional requirements.

8. Testing and Validation

The system was tested throughout development to ensure it met both user and system requirements. Testing focused on core features such as user login, task creation, task completion, deletion, restoring tasks, and calendar updates.

Each function was tested using normal, boundary, and invalid inputs. For example, task creation was tested with valid inputs, empty fields, and incorrect data formats to ensure proper validation. The login system was tested to confirm that only registered users could access the system using session control.

Special attention was given to the calendar feature, where an issue was identified that completed or deleted tasks were still showing as active (green dots). This was resolved by updating the database query to only display tasks where the status is not completed.

Testing confirmed that all major features work as expected and the system meets its original design requirements. Any issues found during testing were fixed and re-tested to ensure reliability and usability.

PLEASE SEE TEST SD2 WORD DOCUMENT IN REPO FOR TEST TABLE.


Section 9:
Overall, the project was successful in meeting its main aim of creating a functional and user-friendly task management system. Most of the core features were implemented correctly, including adding tasks, viewing tasks, completing tasks, restoring tasks, and deleting tasks. The system also includes a mini calendar and a “Due Today” section, which help users organise their tasks more effectively. The interface is clean and consistent across all pages, making it easy to navigate.
One of the main strengths of the project is that it works reliably and all features are connected to the database correctly. The use of session handling ensures that user data is secure and only accessible after login. The system also follows a logical structure, which makes it easy to understand and use.
There was however, there were some challenges during development. An issue that was occuring was with the calendar feature, where completed tasks were still appearing as active (green dots). This required debugging and changes to the database query. There were also some layout inconsistencies between pages that needed to be fixed. In addition, working with PHP and MySQL required time to fully understand how data flows between the frontend and backend.
If the project were to be improved further, several enhancements could be added. For example, the mini calendar could be expanded into a full interactive calendar. Additional features such as notifications, reminders, or task prioritisation filters could also improve usability. The user interface could be further refined with animations and better responsiveness for mobile devices.
In conclusion, the project meets its original requirements and demonstrates a clear understanding of web development concepts, while also showing areas for future improvement.


Section 10:
Finally, it is worth noting that the project has successfully achieved its aim of creating a functional web-based task management system. This is because the system enables clients and users to manage their tasks efficiently through a number of features such as creating, completing, deleting, and restoring tasks.The inclusion of the mini calendar and “Due Today” section further improves usability by helping users keep track of their tasks.
Throughout the development process, key web development concepts were applied, including database integration, session management, and user interface design. The project also demonstrated an understanding of structured development approaches, with features being built and tested incrementally.
All round, the final developed system meets both the user and system requirements and provides a solid foundation for further improvements. The project has also helped develop practical skills in full-stack web development and problem-solving.
























