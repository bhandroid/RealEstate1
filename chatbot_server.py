from flask import Flask, request, jsonify
import mysql.connector
import google.generativeai as genai
import re

app = Flask(__name__)

# Database config
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "realestatephp"
}
conn = mysql.connector.connect(**DB_CONFIG)
cursor = conn.cursor()
print("✅ Connected to MySQL!")

# Gemini config
genai.configure(api_key="AIzaSyBbLb9Li33EJmmAfIM_yg07HF1j0Pmnq18")
model = genai.GenerativeModel('gemini-1.5-pro')

schema_description = """
You are an expert SQL assistant. Convert the following natural language question into a valid MySQL query.

Database Tables:

1. user(user_id, name, email, phone_num, password, role, date_of_creation, reset_token)
   - Each user must have a unique email address.
   - Roles include Buyer, Seller, Agent, Admin.

2. property_listings(property_id, title, description, price, location, property_type, bedrooms, bathrooms, size_sqft, nearest_school, bus_availability, tram_availability, seller_id, status, created_at, zip, street, state, pool_available, is_dog_friendly)
   - 'property_type' can be 'Rental' or 'Sale'.
   - 'status' can be 'available', 'sold', or 'hold'.
   - 'bus_availability', 'tram_availability', 'pool_available', 'is_dog_friendly' are ENUM('Yes','No') fields.

3. property_image(image_id, property_id, image_url)
   - Stores images associated with each property.

4. appointment(appointment_id, user_id, property_id, time, status)
   - Appointment status: Pending, Scheduled, Completed, Cancelled.

5. offer(offer_id, property_id, buyer_id, offer_price, offer_date, status)
   - Offer status: Pending, Accepted, Rejected, Sold.

6. payment(payment_id, offer_id, seller_id, amount_paid, payment_method, payment_date, status, payment_type, rental_interest_id)
   - payment_method: Credit Card, Bank Transfer, PayPal, Stripe.
   - payment_type: Sale or Rental.
   - status: Completed or Pending.
   - Trigger: When a payment status is updated to 'Completed', the linked property is automatically updated to 'sold'.

7. rental_contracts(property_id, available_date, security_deposit)
   - Availability date and security deposit information for rental properties.

8. rental_interest(interest_id, property_id, buyer_id, status, interest_date, payment_status, payment_method, payment_date)
   - Rental interest status: Pending, Accepted, Rejected.
   - payment_status: Pending or Paid.

9. favorite(user_id, property_id, date)
   - Users can mark properties as favorites.

10. review(review_id, buyer_id, agent_id, property_id, rating, comment, date)
    - Users can review agents or properties.

11. chat(chat_id, user_id, role, message, timestamp)
    - Direct chat messages with sender role (buyer/seller/admin).

12. notification(notification_id, user_id, message, status, notification_type, created_at)
    - Notification status: Unread, Read.

13. tickets(ticket_id, user_id, comment, image, timestamp, status, resolution)
    - Ticket status: Open or Resolved.

14. audit_log(log_id, user_id, action_type, action_date, description)
    - Tracks user actions like offer submissions, payments, property edits, or registration.

---

SQL Writing Rules:

- *JOIN Rules:*
  - Always perform all JOINs before WHERE conditions.
  - Use LEFT JOIN for optional tables like property_image, offer, payment.
  - INNER JOIN for required relations (e.g., user linked to property_listings, offers).

- *WHERE Rules:*
  - For rental properties: property_type = 'Rental'.
  - For properties for sale: property_type = 'Sale'.
  - For available properties: status = 'available'.
  - Bus availability: bus_availability = 'Yes'.
  - Tram availability: tram_availability = 'Yes'.
  - For favorite properties: JOIN favorite table using user_id and property_id.
  - For pending offers: offer.status = 'Pending'.
  - For accepted rental interests: rental_interest.status = 'Accepted'.
  - For tickets: tickets.status = 'Open' or 'Resolved'.
  - For completed payments: payment.status = 'Completed'.
  - Match ENUM values exactly (case-sensitive).

- *Selection Rules:*
  - Select useful fields: title, price, location, bedrooms, bathrooms, size_sqft, pool_available, is_dog_friendly.
  - Avoid SELECT * unless explicitly requested.
  - To fetch images, use GROUP_CONCAT(property_image.image_url).

- *GROUP BY Rules:*
  - If using aggregation (e.g., GROUP_CONCAT), always GROUP BY all non-aggregated selected fields.

- *ORDER BY Rules:*
  - Apply sorting if requested, based on price, created_at, or relevant fields.

- *Formatting Rules:*
  - Only return raw SQL query.
  - No explanation.
  - No SQL code block formatting (no ```sql).
  - Table and column names must match exact case sensitivity.
  - Always end SQL with a semicolon (;).

- *Special Cases:*
  - When asking for images, LEFT JOIN property_image and GROUP_CONCAT image URLs.
  - Always fetch location, street, zip, and state fields individually if needed.
  - Mention pool availability and dog-friendliness when requested.
  - Use INNER JOIN when relation is mandatory; otherwise, use LEFT JOIN.
  - Trigger automatically updates property status to 'sold' after payment.

  
  Strict Constraints:
- Only use the tables and columns explicitly mentioned above.
- Do not create or assume any additional tables like 'property_amenities'.
- Only use real columns such as 'location', 'zip', 'street', 'state', etc.
- Amenities are not separately stored. Do not reference amenities.
---
"""


def chatbot_query(user_question):
    prompt = f"""
You are an expert SQL generator.
{schema_description}

User Question:
{user_question}
Just return SQL query , SQl query should not include Backticks
"""
    response = model.generate_content(prompt, generation_config={"temperature": 0.0})
    # ✅ Stronger Cleaning
    sql_query = response.text.strip()

    print(sql_query)
    # Remove all ``` blocks (even if incomplete)
    sql_query = re.sub(r"```.*?```", "", sql_query, flags=re.DOTALL).strip()

    # Remove any single ``` if still left
    sql_query = sql_query.replace("```", "").strip()

    # Remove starting 'sql' if exists
    if sql_query.lower().startswith('sql'):
        sql_query = sql_query[3:].strip()

    # Now remove any unwanted "sql" words inside
    sql_query = re.sub(r"\bsql\b", "", sql_query, flags=re.IGNORECASE).strip()

    # ✅ Final cleanup for case-sensitive ENUM replacements
    sql_query = sql_query.replace("status = 'available'", "status = 'Available'")
    sql_query = sql_query.replace("status = 'sold'", "status = 'Sold'")
    sql_query = sql_query.replace("status = 'pending'", "status = 'Pending'")
    sql_query = sql_query.replace("status = 'accepted'", "status = 'Accepted'")
    sql_query = sql_query.replace("status = 'rejected'", "status = 'Rejected'")
    sql_query = sql_query.replace("status = 'hold'", "status = 'Hold'")
    sql_query = sql_query.replace("property_listings.CONCAT", "CONCAT")

    # ✅ Optional: If you really want, double verify it starts with SELECT
    if not sql_query.lower().startswith("select"):
        raise ValueError("Generated SQL doesn't start with SELECT. Possible bad output.")

    print("\n✅ Final Cleaned SQL to Execute:")
    print(sql_query)

    try:
        cursor.execute(sql_query)
        rows = cursor.fetchall()
        columns = [desc[0] for desc in cursor.description]
        results = [dict(zip(columns, row)) for row in rows]

        if not results:
            return "No matching properties found."

        # Humanized Response
        response = ""
        for property in results:
            parts = []
            if 'title' in property:
                parts.append(f"{property['title']}")
            if 'price' in property:
                parts.append(f"priced at ${property['price']:,.2f}")
            if 'bedrooms' in property and 'bathrooms' in property:
                parts.append(f"with {property['bedrooms']} bedrooms and {property['bathrooms']} bathrooms")
            if 'location' in property:
                parts.append(f"in {property['location']}")
            response += " ".join(parts) + ".\n"
        return response.strip()

    except Exception as e:
        return f"Error: {str(e)}"

@app.route('/chatbot', methods=['POST'])
def chatbot():
    data = request.get_json()
    question = data.get('question', '')
    if not question:
        return jsonify({"answer": "Please ask something."})

    answer = chatbot_query(question)
    return jsonify({"answer": answer})

if __name__ == '__main__':
    app.run(port=5000)
