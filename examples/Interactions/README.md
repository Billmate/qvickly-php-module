# Interaction Examples

This folder contains examples of interactions between different API's.

## Get information on non-activated invoices

[getInfoOnNonActivatedInvoices](getInfoOnNonActivatedInvoices.php)

1. Login through the Auth API to get a token.
2. Use the token to connect to the Portal API.
3. Connect to the Payment API.
4. Fetch 5 invoices from the Portal API that are not activated, contains the text `tess` and where the invoice type if `F` (invoice).
5. Loop through the invoices and fetch the payment from the Payment API.
