# Table of Contents
1. [Database Tables](#database-tables)
2. [Microservices](#microservices)

## Database Tables

### USER
| Column             | Type   | Constraint  |
|--------------------|--------|-------------|
| ID                 | int    | primary key |
| Username           | string |             |
| Password           | string |             |
| Name               | string |             |
| ContactInformation | string |             |

### WEBSITES
| Column      | Type   | Constraint  |
|-------------|--------|-------------|
| ID          | int    | primary key |
| UserID      | int    | ref: > USER.ID |
| URL         | string |             |
| Icon        | string |             |
| Description | string |             |

### EXPERIENCE
| Column      | Type   | Constraint  |
|-------------|--------|-------------|
| ID          | int    | primary key |
| UserID      | int    | ref: > USER.ID |
| CompanyName | string |             |
| Role        | string |             |
| StartDate   | datetime |           |
| EndDate     | datetime |           |
| Description | string |             |

### EDUCATION
| Column         | Type     | Constraint  |
|----------------|----------|-------------|
| ID             | int      | primary key |
| UserID         | int      | ref: > USER.ID |
| InstitutionName | string  |             |
| Degree         | string   |             |
| StartDate      | datetime |             |
| EndDate        | datetime |             |
| Description    | string   |             |

### SKILLS
| Column      | Type   | Constraint  |
|-------------|--------|-------------|
| ID          | int    | primary key |
| UserID      | int    | ref: > USER.ID |
| SkillName   | string |             |
| Proficiency | string |             |

### ROLE
| Column   | Type   | Constraint  |
|----------|--------|-------------|
| ID       | int    | primary key |
| RoleName | string |             |

### USER_ROLE
| Column | Type | Constraint   |
|--------|------|--------------|
| UserID | int  | ref: > USER.ID |
| RoleID | int  | ref: > ROLE.ID |

### DEPARTMENT
| Column | Type   | Constraint  |
|--------|--------|-------------|
| ID     | int    | primary key |
| Name   | string |             |

### PATIENT
| Column           | Type    | Constraint  |
|------------------|---------|-------------|
| ID               | int     | primary key |
| NationalId       | int     |             |
| Name             | string  |             |
| Phone            | string  |             |
| DateOfBirth      | date    |             |
| Gender           | string  |             |
| UHID             | string  |             |
| MedicalHistory   | string  |             |

### DOCTOR
| Column         | Type | Constraint   |
|----------------|------|--------------|
| UserID         | int  | ref: > USER.ID |
| DepartmentID   | int  | ref: > DEPARTMENT.ID |
| Description    | string |            |
| IncomePercentage | int  |            |

### RECEPTIONIST
| Column | Type | Constraint   |
|--------|------|--------------|
| UserID | int  | ref: > USER.ID |



## Microservices

### 1. User Management
#### Responsibilities
Handle user creation, authentication, and profile management.

#### Endpoints
- `POST /users` - Create user
- `GET /users/{id}` - Get user details
- `PUT /users/{id}` - Update user
- `DELETE /users/{id}` - Delete user
- `POST /auth/login` - Authenticate user

#### Tables
- USER

### 2. Websites Management
#### Responsibilities
Manage websites related to users.

#### Endpoints
- `POST /websites` - Add website
- `GET /websites/{id}` - Get website details
- `PUT /websites/{id}` - Update website
- `DELETE /websites/{id}` - Delete website

#### Tables
- WEBSITES

### 3. Experience Management
#### Responsibilities
Manage user experience records.

#### Endpoints
- `POST /experience` - Add experience
- `GET /experience/{id}` - Get experience details
- `PUT /experience/{id}` - Update experience
- `DELETE /experience/{id}` - Delete experience

#### Tables
- EXPERIENCE

### 4. Education Management
#### Responsibilities
Manage user education records.

#### Endpoints
- `POST /education` - Add education record
- `GET /education/{id}` - Get education details
- `PUT /education/{id}` - Update education
- `DELETE /education/{id}` - Delete education

#### Tables
- EDUCATION

### 5. Skills Management
#### Responsibilities
Manage user skills.

#### Endpoints
- `POST /skills` - Add skill
- `GET /skills/{id}` - Get skill details
- `PUT /skills/{id}` - Update skill
- `DELETE /skills/{id}` - Delete skill

#### Tables
- SKILLS


#### Tables
- ROLE
- USER_ROLE

### 7. Department Management
#### Responsibilities
Manage departments within the clinic.

#### Endpoints
- `POST /departments` - Create department
- `GET /departments/{id}` - Get department details
- `PUT /departments/{id}` - Update department
- `DELETE /departments/{id}` - Delete department

#### Tables
- DEPARTMENT

### 8. Patient Management
#### Responsibilities
Handle patient information.

#### Endpoints
- `POST /patients` - Create patient
- `GET /patients/{id}` - Get patient details
- `PUT /patients/{id}` - Update patient
- `DELETE /patients/{id}` - Delete patient

#### Tables
- PATIENT

### 9. Doctor Management
#### Responsibilities
Manage doctors' information.

#### Endpoints
- `POST /doctors` - Create doctor
- `GET /doctors/{id}` - Get doctor details
- `PUT /doctors/{id}` - Update doctor
- `DELETE /doctors/{id}` - Delete doctor

#### Tables
- DOCTOR

### 10. Receptionist Management
#### Responsibilities
Manage receptionists' information.

#### Endpoints
- `POST /receptionists` - Create receptionist
- `GET /receptionists/{id}` - Get receptionist details
- `PUT /receptionists/{id}` - Update receptionist
- `DELETE /receptionists/{id}` - Delete receptionist

#### Tables
- RECEPTIONIST

