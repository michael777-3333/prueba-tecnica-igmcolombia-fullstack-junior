FROM node:20-alpine AS build-stage

WORKDIR /app

COPY package*.json ./
RUN npm install

# Aumentar memoria y saltar type checking
ENV NODE_OPTIONS="--max-old-space-size=4096"

COPY . .

# Saltar verificación de TypeScript
RUN npm run build --skipLibCheck
# O si no funciona:
RUN npx vue-tsc --skipLibCheck && npm run build

FROM nginx:stable-alpine AS production-stage
COPY --from=build-stage /app/dist /usr/share/nginx/html
COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
