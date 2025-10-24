# DBMS Project Summary

---

## Requirements

 The project will be a **DBMS project**.

### Architecture & Technologies
* The **backend** will function as an **API system**, where data is sent or received using API calls via routes.
* The **Frontend** will use **Vue.js** or another faculty-approved framework.
    * Specific plans for the frontend include using the **Vue.js** framework.
    * **Ionic** and the **Capacitor plugin** will also be used to convert the website into a **mobile application**.
* The **Backend** will use **Laravel** or any other **MVC architecture framework**.
    * It will be an **API-based system** rather than using Livewire.
* For the **Database**, the faculty will decide on **MySQL, PostgreSQL, or SQLite**.
    * If there is freedom to choose, the preferred database is **MySQL**.
* For the **Admin Panel**, the **Laravel Filament framework** will be used, as it is easier than creating admin pages from scratch.

---

## Project Structure

 The project will contain **three main folders**: **Frontend**, **Backend**, and **Admin**.

* **Frontend**: Contains the frontend code.
* **Backend**: Contains the backend code.
* **Admin**: Contains the code for the **Admin panel**, which will be **separated from the frontend** part.


---

## Git System (GLT System)

* There will be a **repository** for this DBMS project.
* The **main branch** will be the **DEV branch**.
* For every new feature, a new branch will be created from the DEV branch.
    * Feature branches should be named, for example, `FEATURE_graph` for a graph feature.
* After writing every workable function, the user must **commit** to that feature branch.
* Once a branch is created, the user needs to create a **pull request** against the **DEV branch**.