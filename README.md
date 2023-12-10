## TO DO Application with Service Repository Pattern Implementation For API Security And Well Structuring

<!-- ## Things Involved ## -->

1. Service Provider for dependancy injection of the service class via singleton implementation to create the instance for first time only and use that through out the application.
2. Complete CRUD for TO DO Application.
3. MarkAsComplete and MarkAsInComplete APIs.
4. Proper RESTful API Structure with JSON Response.
5. Implemented API Versoning
6. Extra thing I have done for my learning is that I have created a command in Console Directory, the command is for API Versoning, currently I have used V1 for the APIs but that command accepts few arguement ModelName MigrationFLag and APIVersion, and creates a fully fledged Service Repo Directory Structure and with API versioning.
7. Implemented Pagination as well.
8. It's only APIs so no front end and blade files are present.

## command is: php artisan create:all ModelName MigrationFlag apiVersion

<!-- ## How To Run ## -->

1. Run migration
2. You can use API endpoints in POSTMAN to test the APIs
3. Endpoints can be found in api.php
4. update env file for DB creds.
5. RUN Composer Install.
