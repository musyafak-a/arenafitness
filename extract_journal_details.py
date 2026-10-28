import sys
import json
import re

with open('journal_analysis.txt', 'r', encoding='utf-8') as f:
    text = f.read()

journals_raw = text.split('=== [')

results = []

for idx, j in enumerate(journals_raw[1:]):
    header_end = j.find('\n')
    header = j[:header_end]
    content = j[header_end:]
    
    # Store filename
    fname_match = re.search(r'\d+\]\s+(.*?)\s+\(Pages:', header)
    fname = fname_match.group(1) if fname_match else header
    
    # Let's collect first 3000 chars and last 3000 chars
    first_part = content[:4000]
    last_part = content[-4000:]
    
    results.append({
        'index': idx + 1,
        'filename': fname,
        'header': header,
        'first_part': first_part,
        'last_part': last_part,
        'full_length': len(content)
    })

with open('extracted_raw_summaries.json', 'w', encoding='utf-8') as f:
    json.dump(results, f, ensure_ascii=False, indent=2)

print('Saved extracted_raw_summaries.json with', len(results), 'journals.')
