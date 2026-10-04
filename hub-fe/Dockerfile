### build app

FROM node:22-slim AS builder

WORKDIR /app

COPY package*.json ./

# RUN npm ci --legacy-peer-deps
RUN yarn install --frozen-lockfile

COPY . .

RUN yarn build

### serve app

FROM alpine:3.20

# Install http-server globally using a lightweight Node/NPM setup
RUN apk add --no-cache nodejs npm && \
    npm install -g http-server && \
    mkdir /app && \
    addgroup -S appgroup && adduser -S appuser -G appgroup && \
    chown appuser:appgroup /app

WORKDIR /app

# Copy ONLY the compiled static files from the builder stage
COPY --from=builder --chown=appuser:appgroup /app/dist ./dist

# Switch to the non-root user for security
USER appuser

EXPOSE 3001

CMD [ "http-server", "dist", "-p", "3001" ]
