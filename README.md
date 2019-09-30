# OrderExport

Create a simple Magneto Extension

When specific SKU(s) are ordered it will POST information from the order through to an API via json that is secured with basic authentication

Details to pass:
- SKU
- Custom options on ordered item
- Qty / Price / Discount
- Customer ID, name, phone and address details (shipping and billing)
## Bonus: 
- Admin interface to set SKU(s) it should fire on, and API endpoint

## Implementation notes:
For now order export is called in observer of event sales_order_place_after. It's OK as test/demo solution. For live
solution we should implement possibility to re-export orders if anything wrong happened with API server during observer
processing. Solution could be:
- Add new attribute to order as flag for re-export
- Set flag to order if API call is failed
- Reexport all flagged orders using cron
