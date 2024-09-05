# List kode yang ingin diubah
codes = ['BJA', 'CWD', 'MJY', 'PDL', 'PNL','SOR','RJW','CCL','BTJ','CKW','CCL','CPT','CSA','GNH','LEM','RCK']

# Fungsi untuk mengubah format setiap kode menjadi <option>
def generate_options(codes):
    options = []
    for code in codes:
        option = f'<option value="{code}" {{ old('STO', $ticket->STO) == '{code}' ? 'selected' : '' }}></option>'
                    
        options.append(option)
    return options

# Hasilkan dan cetak opsi
generated_options = generate_options(codes)
for option in generated_options:
    print(option)