# API Documentation

## Overview
This API provides access to various resources and requires authentication via OAuth2.

## Authentication
The API enforces authentication using OAuth2. Clients must obtain an access token by making a request to the OAuth service point. The token must be included in the `Authorization` header as a Bearer token in each API request.

### Obtaining an Access Token
To get an access token, clients must send a POST request to the OAuth token endpoint with the following parameters:

**Endpoint:** `/oauth/access_token`

**Headers:**
- `Content-Type: application/x-www-form-urlencoded`

**Body Parameters:**
- `grant_type`: `client_credentials`
- `client_id`: (your client ID)
- `client_secret`: (your client secret)

**Example Request:**
```sh
curl -X POST "https://your-api.com/oauth/access_token" \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -d "grant_type=client_credentials&client_id=your_client_id&client_secret=your_client_secret"
```

**Response:**
```json
{
  "access_token": "your_access_token",
  "token_type": "Bearer",
  "expires_in": 3600
}
```

## Making Authenticated Requests
Once an access token is obtained, it must be included in the `Authorization` header of each API request.

**Example Request:**
```sh
curl -X GET "https://your-api.com/protected/resource" \
     -H "Authorization: Bearer your_access_token"
```

## Error Handling
If authentication fails, the API will return a `401 Unauthorized` response with an error message.

---

Additional details on OAuth2 authentication implementation can be found in the source code.

