import json
with open('C:/solarshop-scrape/solarshop/storage/app/private/seed/all_products.json', 'r', encoding='utf-8', errors='replace') as f:
    data = json.load(f)
doyin = [p for p in data if 'doyin' in p.get('name', '').lower() or 'doyin' in p.get('brand', '').lower()]
print('Found {} Doyin products'.format(len(doyin)))
for p in doyin:
    print("  - {} | Brand: {} | Price: {} | Regular: {} | Sale: {}".format(p['name'], p.get('brand'), p.get('price'), p.get('regular_price'), p.get('sale_price')))