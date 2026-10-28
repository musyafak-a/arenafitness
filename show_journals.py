import json

with open('extracted_raw_summaries.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

with open('journals_overview.txt', 'w', encoding='utf-8') as out:
    for item in data:
        out.write(f"=== JOURNAL {item['index']}: {item['filename']} ===\n")
        lines = item['first_part'].splitlines()
        for l in lines[:40]:
            if l.strip():
                out.write('   ' + l.strip() + '\n')
        out.write('\n--- LAST PART ---\n')
        last_lines = item['last_part'].splitlines()
        for l in last_lines[-35:]:
            if l.strip():
                out.write('   ' + l.strip() + '\n')
        out.write('\n' + '='*70 + '\n\n')

print('Wrote journals_overview.txt')
