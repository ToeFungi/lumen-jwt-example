# Lumen JWT Example
This project is based on [lcobucci/jwt](https://github.com/lcobucci/jwt) and demonstrates how easy it is to use JWT tokens.

### Dependencies
- PHP 5.5+ (v3.2) and PHP 7.1 (v4.x)
- Docker
- Docker-compose

## Installation
Include the host `lumen_jwt` in your hosts file. On linux this is `/etc/hosts`.
Now you can run the Docker container.
```shell
docker-compose up -d web
```

## Basic usage 
Hit the endpoint `/token` with a `GET` to get a JWT token.


Hit the same endpoint as a `POST` with the token set as a header, 
`authorization: Bearer (token)`.

You can expect a `200` with `Valid token` as a response. 


If you break the token, you can expect a `403`
If the token is used after 2 hours, you can expect a `401`

## Tools
It is recommended that **Insomnia** is used to fire HTTP requests to the endpoints. 