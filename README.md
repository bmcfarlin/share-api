# API Documentation

## Overview
This API provides endpoints for managing affiliates, wallets, and transactions. It supports CRUD operations through RESTful methods (GET, POST, PUT, DELETE)  and requires authentication via OAuth2.

## Base URL
```
/en/api/
```
## Authentication
The API enforces authentication using OAuth2. Clients must obtain an access token by making a request to the OAuth service point. The token must be included in the `Authorization` header as a Bearer token in each API request.

### Obtaining an Access Token
To get an access token, clients must send a POST request to the OAuth token endpoint with the following parameters:

**Endpoint:** `/oauth/access-token`

**Headers:**
- `Content-Type: application/x-www-form-urlencoded`

**Body Parameters:**
- `grant_type`: `client_credentials`
- `client_id`: (your client ID)
- `client_secret`: (your client secret)

**Example Request:**
```sh
curl -X POST "https://your-api.com/oauth/access-token" \
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


---
## Endpoints

### 1. Affiliate
#### `GET /api/affiliate/{id}`
Retrieve details about an affiliate.
##### Request Parameters
- `id` (integer, required) - The ID of the affiliate.
##### Response
```json
{
  "status": "success",
  "items": [
    {
      "affiliate_id": 123,
      "name": "John Doe"
    }
  ]
}
```

#### `POST /api/affiliate`
Create a new affiliate.
##### Request Body
- `partner_id` (integer, required) - ID of the partner.
- `email` (string, required) - Email of the affiliate.
##### Response
```json
{
  "status": "success",
  "items": [{ "affiliate_id": 124 }]
}
```

#### `PUT /api/affiliate/{id}`
Update an affiliate.
##### Request Parameters
- `id` (integer, required) - The ID of the affiliate.
##### Request Body
- `code` (string, required) - The updated code for the affiliate.
##### Response
```json
{
  "status": "success",
  "items": [{ "affiliate_id": 123, "code": "new_code" }]
}
```

#### `DELETE /api/affiliate/{id}`
Delete an affiliate.
##### Request Parameters
- `id` (integer, required) - The ID of the affiliate.
##### Response
```json
{
  "status": "success"
}
```

---
### 2. Wallet
#### `GET /api/wallet/{id}`
Retrieve wallet details.
##### Request Parameters
- `id` (integer, required) - The ID of the wallet.
##### Response
```json
{
  "status": "success",
  "items": [{ "wallet_id": 456, "name": "default" }]
}
```

---
### 3. Transactions
#### `GET /api/tx/{id}`
Retrieve transaction details.
##### Request Parameters
- `id` (integer, required) - The ID of the transaction.
##### Response
```json
{
  "status": "success",
  "items": [{ "tx_id": 789, "amount": 100.00 }]
}
```

#### `POST /api/tx`
Create a transaction.
##### Request Body
- `partner_id` (integer, required)
- `email` (string, required)
- `amount` (float, required)
- `tx_type_cd` (string, required)
##### Response
```json
{
  "status": "success",
  "items": [{ "tx_id": 790, "amount": 150.00 }]
}
```

#### `PUT /api/tx/{id}`
Update a transaction.
##### Request Parameters
- `id` (integer, required)
##### Request Body
- `amount` (float, required)
##### Response
```json
{
  "status": "success",
  "items": [{ "tx_id": 789, "amount": 200.00 }]
}
```

#### `DELETE /api/tx/{id}`
Delete a transaction.
##### Request Parameters
- `id` (integer, required)
##### Response
```json
{
  "status": "success"
}
```

## Error Handling
Responses include an error message in case of failure:
```json
{
  "status": "error",
  "error": "invalid request 305"
}
```

## Method Override
This API supports method override using headers:
- `X-HTTP-Method-Override`
- `USER_METHOD_OVERRIDE`

- okay