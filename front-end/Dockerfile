# Use an official Node.Js image
FROM node:20

# Set working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package*.json ./
RUN npm install

# Copy the rest of the application code
COPY . .

# Run the build
RUN npm run build

# Expose on port 3000
EXPOSE 3000

# Start the server
CMD [ "npm", "run", "dev" ]