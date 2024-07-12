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

### VISIT
| Column     | Type     | Constraint   |
|------------|----------|--------------|
| ID         | int      | primary key  |
| PatientID  | int      | ref: > PATIENT.ID |
| DoctorID   | int      | ref: > DOCTOR.UserID |
| DateTime   | datetime |              |
| StartTime  | datetime |              |
| EndTime    | datetime |              |
| Reason     | string   |              |
| Diagnosis  | string   |              |
| Prescription | string |              |
| Status     | string   |              |
| Type       | string   |              |

### ORDER
| Column      | Type   | Constraint   |
|-------------|--------|--------------|
| ID          | int    | primary key  |
| VisitID     | int    | ref: > VISIT.ID |
| Name        | string |              |
| Type        | string |              |
| Description | string |              |
| ProvidedBy  | string |              |
| Result      | string |              |
| Status      | string |              |

### LABREPORTS
| Column | Type | Constraint   |
|--------|------|--------------|
| OrderID | int | primary key, ref: > ORDER.ID |

### RADIOLOGY
| Column             | Type   | Constraint   |
|--------------------|--------|--------------|
| OrderID            | int    | primary key, ref: > ORDER.ID |
| ExaminationReason  | string |              |

### PROCEDURESERVICES
| Column         | Type   | Constraint   |
|----------------|--------|--------------|
| ID             | int    | primary key  |
| VisitID        | int    | ref: > VISIT.ID |
| DepartmentID   | int    | ref: > DEPARTMENT.ID |
| ProvidedBy     | int    | ref: > DOCTOR.UserID |
| Name           | string |              |
| Note           | string |              |

### PAYMENT
| Column  | Type   | Constraint   |
|---------|--------|--------------|
| ID      | int    | primary key  |
| VisitID | int    | ref: > VISIT.ID |
| Amount  | float  |              |
| Date    | date   |              |

### VITALS
| Column         | Type   | Constraint   |
|----------------|--------|--------------|
| ID             | int    | primary key  |
| VisitID        | int    | ref: > VISIT.ID |
| Temperature    | float  |              |
| Pressure       | float  |              |
| Pulse          | int    |              |
| Height         | float  |              |
| Weight         | float  |              |
| SpO2           | float  |              |
| RespiratoryRate | int   |              |

### INVENTORY
| Column   | Type   | Constraint   |
|----------|--------|--------------|
| ID       | int    | primary key  |
| Name     | string |              |
| Type     | string |              |
| Quantity | int    |              |
| Unit     | string |              |

### VISIT_INVENTORY
| Column      | Type | Constraint   |
|-------------|------|--------------|
| VisitID     | int  | ref: > VISIT.ID |
| InventoryID | int  | ref: > INVENTORY.ID |
| Count       | int  |              |

### DOCUMENTS
| Column    | Type     | Constraint   |
|-----------|----------|--------------|
| ID        | int      | primary key  |
| VisitID   | int      | ref: > VISIT.ID |
| Template  | string   |              |
| NoteType  | string   |              |
| Date      | datetime |              |
| DoctorSign | string  |              |

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

### 6. Role Management
#### Responsibilities
Manage user roles.

#### Endpoints
- `POST /roles` - Create role
- `GET /roles/{id}` - Get role details
- `PUT /roles/{id}` - Update role
- `DELETE /roles/{id}` - Delete role
- `POST /user-roles` - Assign role to user

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

### 11. Visit Management
#### Responsibilities
Handle visits and related information.

#### Endpoints
- `POST /visits` - Create visit
- `GET /visits/{id}` - Get visit details
- `PUT /visits/{id}` - Update visit
- `DELETE /visits/{id}` - Delete visit

#### Tables
- VISIT
- VITALS
- DOCUMENTS

### 12. Order Management
#### Responsibilities
Manage orders related to visits.

#### Endpoints
- `POST /orders` - Create order
- `GET /orders/{id}` - Get order details
- `PUT /orders/{id}` - Update order
- `DELETE /orders/{id}` - Delete order

#### Tables
- ORDER

### 13. Lab Reports Management
#### Responsibilities
Handle lab reports.

#### Endpoints
- `POST /lab-reports` - Create lab report
- `GET /lab-reports/{id}` - Get lab report details
- `PUT /lab-reports/{id}` - Update lab report
- `DELETE /lab-reports/{id}` - Delete lab report

#### Tables
- LABREPORTS

### 14. Radiology Management
#### Responsibilities
Manage radiology reports.

#### Endpoints
- `POST /radiology` - Create radiology report
- `GET /radiology/{id}` - Get radiology report details
- `PUT /radiology/{id}` - Update radiology report
- `DELETE /radiology/{id}` - Delete radiology report

#### Tables
- RADIOLOGY

### 15. Procedure Services Management
#### Responsibilities
Manage procedure services related to visits.

#### Endpoints
- `POST /procedure-services` - Create procedure service
- `GET /procedure-services/{id}` - Get procedure service details
- `PUT /procedure-services/{id}` - Update procedure service
- `DELETE /procedure-services/{id}` - Delete procedure service

#### Tables
- PROCEDURESERVICES

### 16. Payment Management
#### Responsibilities
Handle payments.

#### Endpoints
- `POST /payments` - Create payment
- `GET /payments/{id}` - Get payment details
- `PUT /payments/{id}` - Update payment
- `DELETE /payments/{id}` - Delete payment

#### Tables
- PAYMENT

### 17. Vitals Management
#### Responsibilities
Handle vitals data related to visits.

#### Endpoints
- `POST /vitals` - Add vitals
- `GET /vitals/{id}` - Get vitals details
- `PUT /vitals/{id}` - Update vitals
- `DELETE /vitals/{id}` - Delete vitals

#### Tables
- VITALS

### 18. Inventory Management
#### Responsibilities
Manage clinic inventory.

#### Endpoints
- `POST /inventory` - Add inventory item
- `GET /inventory/{id}` - Get inventory item details
- `PUT /inventory/{id}` - Update inventory item
- `DELETE /inventory/{id}` - Delete inventory item

#### Tables
- INVENTORY

### 19. Visit Inventory Management
#### Responsibilities
Manage inventory usage related to visits.

#### Endpoints
- `POST /visit-inventory` - Add visit inventory usage
- `GET /visit-inventory/{id}` - Get visit inventory usage details
- `PUT /visit-inventory/{id}` - Update visit inventory usage
- `DELETE /visit-inventory/{id}` - Delete visit inventory usage

#### Tables
- VISIT_INVENTORY

### 20. Documents Management
#### Responsibilities
Handle documents related to visits.

#### Endpoints
- `POST /documents` - Add document
- `GET /documents/{id}` - Get document details
- `PUT /documents/{id}` - Update document
- `DELETE /documents/{id}` - Delete document

#### Tables
- DOCUMENTS
  -
