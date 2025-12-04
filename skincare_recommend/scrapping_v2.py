import csv
import time
from selenium import webdriver
from selenium.webdriver.edge.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from bs4 import BeautifulSoup

# --- Konfigurasi ---
driver = webdriver.Edge() 
BASE_URL = "https://pubchem.ncbi.nlm.nih.gov/#query="
INPUT_FILE = "keywords.txt"
OUTPUT_FILE = "synonym.csv"
# Waktu tunggu implisit untuk elemen dimuat
driver.implicitly_wait(10)
# Waktu tunggu eksplisit untuk elemen tertentu
WAIT_TIME = 20

def scrape_pubchem():
    """
    Fungsi utama untuk melakukan scraping sinonim dari PubChem.
    Menambahkan logika untuk melewatkan (skip) kata kunci yang menghasilkan
    judul senyawa (main_key) yang sudah pernah diproses.
    """
    print("🚀 Memulai proses scraping...")
    
    # Set untuk menyimpan nama senyawa yang sudah berhasil diproses
    # Ini adalah cara tercepat untuk mengecek duplikasi.
    processed_compounds = set() 
    
    # Membuka file output CSV dan menulis header
    with open(OUTPUT_FILE, 'w', newline='', encoding='utf-8') as csvfile:
        csv_writer = csv.writer(csvfile)
        # Menulis header: 'kata kunci', 'synonim'
        csv_writer.writerow(['kata kunci', 'synonim'])
        
        # Membaca daftar kata kunci dari file teks
        try:
            with open(INPUT_FILE, 'r', encoding='utf-8') as f:
                keywords = [line.strip() for line in f if line.strip()]
        except FileNotFoundError:
            print(f"❌ ERROR: File {INPUT_FILE} tidak ditemukan.")
            driver.quit()
            return
            
        print(f"🔎 Ditemukan {len(keywords)} kata kunci untuk di-scrape.")

        for keyword in keywords:
            # Menggunakan keyword yang sudah di-strip untuk pencarian
            clean_keyword = keyword.strip()
            print(f"\n--- Memproses kata kunci: **{clean_keyword}** ---")
            
            # 1 & 2. Mengakses URL pencarian
            search_url = f"{BASE_URL}{clean_keyword}"
            print(f"  - Mengunjungi URL pencarian: {search_url}")
            driver.get(search_url)

            # 3. Mencari link "Summary" di dalam div "featured-result"
            try:
                # Perbaikan: ID seharusnya "featured-result" (singular)
                featured_result_div = WebDriverWait(driver, WAIT_TIME).until(
                    EC.presence_of_element_located((By.ID, "featured-results")) 
                )
                
                # Mencari elemen <a> dengan teks "Summary"
                summary_link = featured_result_div.find_element(
                    By.XPATH, ".//a[text()='Summary']"
                )
                
                next_url = summary_link.get_attribute('href')
                print(f"  - Link 'Summary' ditemukan.")

            except Exception:
                print(f"  - ⚠️ GAGAL menemukan hasil unggulan atau link 'Summary' untuk '{clean_keyword}'. Melanjutkan.")
                continue

            # 4. Mengalihkan scraping ke link "Summary"
            print(f"  - Mengalihkan ke halaman detail...")
            driver.get(next_url)
            
            # Menunggu H1 dimuat
            try:
                WebDriverWait(driver, WAIT_TIME).until(
                    EC.presence_of_element_located((By.TAG_NAME, "h1"))
                )
            except Exception:
                 print(f"  - ⚠️ GAGAL memuat H1 di halaman detail untuk '{clean_keyword}'. Melanjutkan.")
                 continue

            # 5. Mencari elemen <h1> dan <section> Sinonim
            
            soup = BeautifulSoup(driver.page_source, 'html.parser')
            
            # LOGIKA PENGAMBILAN H1 (KUNCI UTAMA)
            all_h1_elements = soup.find_all('h1')
            main_key = ""

            # Jika menggunakan indeks 1 (seperti di kode Anda sebelumnya):
            if len(all_h1_elements) > 1:
                # Mengambil elemen kedua (indeks 1) dan mengubah ke huruf kecil
                main_key = all_h1_elements[1].text.strip().lower()
            elif all_h1_elements:
                 # Fallback: mengambil elemen H1 pertama
                main_key = all_h1_elements[0].text.strip().lower()
            else:
                main_key = "nama tidak ditemukan"
            
            print(f"  - Kunci Utama (H1) yang ditemukan: **{main_key}**")
            
            # --- MEKANISME PENCEGAHAN DUPLIKASI BARU ---
            if main_key in processed_compounds:
                print(f"  - ⏩ SKIP: Senyawa '{main_key}' sudah pernah diproses.")
                continue # Langsung lanjut ke kata kunci berikutnya

            if main_key == "nama tidak ditemukan" or "javascript is required" in main_key:
                print("  - ⚠️ Kunci Utama tidak valid, melewati pemrosesan sinonim.")
                continue
            
            # Mencari bagian sinonim
            synonym_section = soup.find('section', id="Depositor-Supplied-Synonyms")
            
            synonym_list = []
            if synonym_section:
                li_elements = synonym_section.find_all('li')
                
                for li in li_elements:
                    synonym = li.text.strip().lower()
                    if synonym:
                        synonym_list.append(synonym)
                
                print(f"  - Ditemukan {len(synonym_list)} sinonim.")
            else:
                print("  - ⚠️ Bagian 'Depositor-Supplied-Synonyms' tidak ditemukan.")
            
            # 6. Menyimpan data ke file CSV
            if synonym_list:
                for synonym in synonym_list:
                    # Format: kata kunci, synonim
                    csv_writer.writerow([main_key, synonym])
                print("  - Data berhasil ditulis ke CSV.")
                
                # **TANDA PENTING:** Tambahkan kunci ini ke set yang sudah diproses
                processed_compounds.add(main_key) 
            else:
                print("  - Tidak ada sinonim yang ditemukan, tidak ada data yang ditulis.")


    print("\n✅ Proses scraping SELESAI. Data disimpan dalam **synonym.csv**.")
    driver.quit()

if __name__ == "__main__":
    scrape_pubchem()