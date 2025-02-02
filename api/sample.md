# 🔹 Example API Calls

## 1️⃣ Get all events

🔗 `GET /api/events.php`

📌 **Response**:

```json
{
    "status": "success",
    "events": [
        { "id": 1, "name": "Tech Conference", "description": "A conference on AI", "max_capacity": 100, "created_by": 5 },
        { "id": 2, "name": "Music Fest", "description": "Live Music Festival", "max_capacity": 500, "created_by": 3 }
    ]
}
```

## 2️⃣ Get a specific event

🔗 `GET /api/events.php?event_id=1`

📌 **Response:**

```json
{
    "status": "success",
    "event": { "id": 1, "name": "Tech Conference", "description": "A conference on AI", "max_capacity": 100, "created_by": 5 }
}
```
