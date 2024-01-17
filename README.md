# Code Challenge: Machine Translation Microservice

## Description

To facilitate all machine translation jobs at our company, we require a machine translation microservice. This service should provide machine translations of texts submitted by various systems or users, and it will use external services to translate the texts. When such an external service is not available, there should be fallback to a secondary service. In the future, there will be more fallbacks, so this should be considered.

You are free to use any machine translation platform, but you should use at least two (one as a fallback). For example, **DeepL** and **LectoAI** offer free accounts and APIs, but:

- Do not use a third-party API connector package for the platforms of your choice, write your own implementation.
- The microservice should be able to receive translation requests and allow querying the results of previously made requests, through an API (JSON).
- We also want to keep logs for each translation processed through this microservice.
- To maximize the performance of the service, translations should occur asynchronously (use the queuing technique of your choice).

Also, please make sure:

- The microservice is horizontally scalable.
- The code includes at least one unit and one functional test case.
- Include a readme that briefly explains the choices you made and why.

### Out of scope

- No authentication/authorization is required for this microservice for now.

### Requirements

- Docker
- PHP
- MySQL, PostgreSQL, MongoDB, or any other data storage

### Bonus (optional)

- CLI command to receive translation requests and allow querying the results of requests.

### Preferred technologies

- Symfony/Laravel

---

## Done?

Push all the commits to a GitHub repository and share the URL.

- **_Important_**: Only **Docker** will run on our machines, and we should not need to install any additional software to run this microservice.

---

## Author

- **Jorge García Torralba** &#8594; [jorge-garcia](https://github.com/jgarciatorralba)
