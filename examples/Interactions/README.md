# Interaction Examples

This folder contains examples of interactions between different API's.

## Get information on non-activated invoices

[getInfoOnNonActivatedInvoices](getInfoOnNonActivatedInvoices.php)

1. Login through the Auth API to get a token.
2. Use the token to connect to the Portal API.
3. Connect to the Payment API.
4. Fetch 5 invoices from the Portal API that are not activated, contains the text `tess` and where the invoice type if `F` (invoice).
5. Loop through the invoices and fetch the payment from the Payment API.

## Update status log of invoice by invoice number

The normal way to update the status log through the Payment API is to use the `updateStatusLogOfInvoiceByHash` method. That will require us to know the hash of the invoice. But if we only know the invoice number then we will have to ask the Portal API for the hash. This example shows how to do that.

1. Login through the Auth API to get a token.
2. Use the token to connect to the Portal API.
3. Connect to the Payment API.
4. Fetch the invoice from the Portal API by invoice number.
5. Get the hash from the invoice data.
6. Update the status log of the invoice by hash in the Payment API.