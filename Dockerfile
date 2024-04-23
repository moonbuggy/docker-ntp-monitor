# syntax = docker/dockerfile:1.4.0

ARG PHP_VERSION="latest"
ARG FROM_IMAGE="moonbuggy2000/alpine-s6-nginx:${PHP_VERSION}"

FROM "${FROM_IMAGE}"

COPY ./root ./
