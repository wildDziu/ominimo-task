```markdown
# Project Setup Instructions

## Prerequisites

Before running the project, make sure you have the following software installed on your system:

1. **Docker**: Download and install Docker from [Docker's official site](https://www.docker.com/products/docker-desktop).
2. **Docker Compose**: This typically comes bundled with Docker. You can verify its installation by running:
   ```bash
   docker-compose --version
   ```
   If it’s not installed, follow the instructions from the [Docker Compose documentation](https://docs.docker.com/compose/install/).

## Setup Instructions

### 1. Download the Project

Since the repository is private, you will need to download the project manually:

- Go to the repository: [https://github.com/dziulatex/ominimo-task](https://github.com/dziulatex/ominimo-task).
- Click the **Code** button and select **Download ZIP**.
- Unzip the downloaded archive to a location of your choice.

### 2. Navigate to the Project Directory

After unpacking the archive, open a terminal and navigate to the directory where the `docker-compose.yml` file is located. For example, if you extracted the project to your `Downloads` folder, you would run the following command:

```bash
cd ~/Downloads/ominimo-task
```

### 3. Start the Docker Containers

Once in the project directory, you can bring up the Docker containers by running:

```bash
docker-compose up -d
```

This command will start the containers in the background.

### 4. Access the Project

After the Docker containers are up and running, you can access the project by opening your browser and navigating to:

```bash
http://localhost
```

### Additional Commands

- To monitor the logs of the running Docker containers, you can use the following command:
  ```bash
  docker-compose logs -f
  ```

- To stop the Docker containers, run:
  ```bash
  docker-compose down
  ```

## Conclusion

You should now have the project running locally. If you encounter any issues, ensure Docker and Docker Compose are installed correctly and that the containers are running.
```
