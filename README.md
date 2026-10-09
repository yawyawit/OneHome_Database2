SYSTEM REQUIREMENTS
* Functional Requirements
  - User registration and authentication = Establish customer and provider identities.
  - Service catalog management = Define available services, categories, and descriptions.
  - Provider profile management = Store skills, service areas, pricing, and credentials.
  - Service request creation = Capture service type, location, schedule, and problem description.
  - Provider discovery and matching = Identify eligible providers based on service, location, and availability.
  - Booking and scheduling = Reserve a provider and establish an agreed appointment.
  - Quote and pricing management = Communicate estimated or agreed service costs.
  - Booking lifecycle management = Track confirmation, progress, completion, cancellation, and disputes.
  - Payment processing = Collect customer payments and track payment status.
  - Provider payouts = Record and initiate provider compensation.
  - Notifications and messaging = Communicate booking updates and coordinate service delivery.
  - Notifications and messaging = Communicate booking updates and coordinate service delivery.
  - Dispute and refund management = Handle disagreements, refunds, and service issues.
  - Administrative management = Manage users, providers, service categories, and operational issues.

* Non-functional Requirements
  - Reliability = Prevent lost bookings, payments, and critical state changes.
  - Consistency = Maintain a single valid booking state and accurate financial records.
  - Security = Protect identities, addresses, payment references, and account access.
  - Availability = Keep essential booking and service-management functions accessible.
  - Performance = Return search results and booking responses within acceptable latency targets.
  - Scalability = Support growth in users, providers, bookings, and geographic coverage.
  - Observability = Record logs, metrics, and traces to diagnose failures.
  - Maintainability = Keep business rules understandable and testable.
  - Privacy = Restrict access to customer and provider information based on legitimate need.
  - Recoverability = Recover from service outages, interrupted workflows, and data

ACTORS
1. Customer = The person requesting a home service.
  Responsibilities:
    - Browse services and providers.
    - Submit service requests.
    - Compare quotes and select providers.
    - Schedule, pay for, and review services.
    - Cancel bookings or report problems

2. Service Provider = The individual or business performing the requested service.
  Responsibilities:
    - Maintain a profile, service offerings, and availability.
    - Receive and respond to service requests.
    - Submit quotes and accept bookings.
    - Perform the service and report completion.

3. Administrator / Operations Staff = The actor responsible for platform governance and operational intervention.
  Responsibilities:
    - Verify providers and manage accounts.
    - Maintain service categories and platform rules.
    - Investigate disputes and manage refunds.
    - Monitor suspicious activity and operational issues.

4. Payment Gateway = An external system that processes payment operations.
  Responsibilities:
    - Authorize or capture payments.
    - Report payment success or failure.
    - Support refunds and payment reconciliation.
      
5. Notification Service = An external delivery system for push notifications, SMS, or email.
  Responsibilities:
    - Deliver booking confirmations and reminders.
    - Notify customers and providers about important changes.

TOP USE CASES
Primary actor: Customer
Supporting actor: Service Provider
**Preconditions**
- The customer is authenticated.
- A valid service request exists.
- At least one provider has submitted a valid quote.
- The quote has not expired or been withdrawn.

**Main flow**
- The customer reviews a provider's quote.
- The customer selects an available appointment.
- The system validates the quote, provider availability, and booking conditions.
- The system reserves the appointment.
- The system records the agreed price and terms.
- The booking is confirmed.
- Both parties receive a confirmation.

**Alternative flows**
- If the provider is no longer available, the customer selects another slot.
- If the quote has expired, the customer requests a new quote.
- If payment authorization is required and fails, the booking remains unconfirmed or is released.
- If the customer cancels before confirmation, the request remains unbooked.

**Postconditions**
A confirmed booking exists with an agreed provider, service, appointment,
and price.
<img width="605" height="547" alt="image" src="https://github.com/user-attachments/assets/74591c1b-18be-4301-be49-3d0d5c7a6d44" />

CLASS DIAGRAM
<img width="606" height="685" alt="image" src="https://github.com/user-attachments/assets/aabe4445-85fa-4e06-b479-9854037ae40e" />
User → Customer / Provider: Both roles share identity and authentication information. A user may hold both roles if the product permits it.

ServiceOffering → ServiceRequest: A provider's offering defines the service that can be requested. The request should retain a snapshot of relevant service details so later catalog changes do not rewrite historical transactions.

ServiceRequest → Quote: Multiple providers may submit competing quotes for the same request.

ServiceRequest → Booking: A request may produce at most one confirmed
booking in this simplified model. If the product allows multiple providers per
request, this relationship must be redesigned.

Booking → Payment: One booking can have multiple payment records, for example, an initial authorization, a final capture, and a refund.

Booking → Payout: Provider compensation is recorded separately from customer payment, allowing platform fees, adjustments, and payout failures to be handled explicitly.

Booking → Review / Dispute: A review or dispute is tied to a particular service transaction rather than to a generic user interaction.

Important design constraints
The class diagram describes conceptual relationships. The implementation
must enforce the following invariants:
- A confirmed booking must reference a valid customer, provider, service, appointment, and agreed price.
- A provider must not have overlapping confirmed bookings when the service requires exclusive time allocation.
- A quote can be accepted only once.
- A payment must not be captured beyond the authorized or otherwise permitted amount.
- A payout must not exceed the amount the provider is entitled to receive.
A review must be tied to an eligible completed booking.
Every financial adjustment must preserve an auditable record.
