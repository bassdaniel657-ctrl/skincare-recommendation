import csv
import time
from selenium import webdriver
from selenium.webdriver.edge.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from bs4 import BeautifulSoup

# --- Konfigurasi ---
# Ganti dengan path ke Edge WebDriver Anda jika tidak ada di PATH sistem
# service = Service(executable_path="path/to/msedgedriver.exe")
# driver = webdriver.Edge(service=service)

# Jika Edge WebDriver sudah ada di PATH sistem, cukup lakukan:
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
    """
    print("🚀 Memulai proses scraping...")
    
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
            print(f"\n--- Memproses kata kunci: **{keyword}** ---")
            
            # 1 & 2. Mengakses URL pencarian
            search_url = f"{BASE_URL}{keyword}"
            print(f"  - Mengunjungi URL pencarian: {search_url}")
            driver.get(search_url)

            # 3. Mencari link "Summary" di dalam div "featured-result"
            try:
                # Menunggu hingga elemen div 'featured-result' muncul
                featured_result_div = WebDriverWait(driver, WAIT_TIME).until(
                    EC.presence_of_element_located((By.ID, "featured-results"))
                )
                
                # Mencari elemen <a> dengan teks "Summary" di dalamnya
                summary_link = featured_result_div.find_element(
                    By.XPATH, ".//a[text()='Summary']"
                )
                
                next_url = summary_link.get_attribute('href')
                print(f"  - Link 'Summary' ditemukan: {next_url}")

            except Exception as e:
                print(f"  - ⚠️ GAGAL menemukan hasil unggulan atau link 'Summary' untuk '{keyword}'. Melanjutkan ke kata kunci berikutnya.")
                # print(f"  - Detail error: {e}") # Opsional: untuk debug
                continue

            # 4. Mengalihkan scraping ke link "Summary"
            print(f"  - Mengalihkan ke halaman detail...")
            driver.get(next_url)
            
            # Menunggu hingga halaman detail selesai dimuat
            # Kita bisa menunggu salah satu elemen kunci (misalnya <h1>) muncul
            try:
                WebDriverWait(driver, WAIT_TIME).until(
                    EC.presence_of_element_located((By.TAG_NAME, "h1"))
                )
            except Exception as e:
                 print(f"  - ⚠️ GAGAL memuat halaman detail untuk '{keyword}'. Melanjutkan ke kata kunci berikutnya.")
                 continue

            # 5. Mencari elemen <h1> dan <section> Sinonim
            
            # Mengambil source code halaman setelah dimuat
            soup = BeautifulSoup(driver.page_source, 'html.parser')
            
            # Mendapatkan kunci utama (biasanya nama zat) dari <h1>
            h1_element = soup.find('h1')
            all_h1_elements = soup.find_all('h1')

# Misalnya, jika h1 nama senyawa selalu adalah elemen kedua (indeks 1)
            if len(all_h1_elements) > 1:
                main_key = all_h1_elements[1].text.strip().lower()
                print(f"  - Kunci Utama (H1): **{main_key}**")
            else:
                main_key = h1_element.text.strip().lower() if h1_element else "Nama Tidak Ditemukan"
                print(f"  - Kunci Utama (H1): **{main_key}**")
            
            # Mencari bagian sinonim yang disuplai oleh depositor
            synonym_section = soup.find('section', id="Depositor-Supplied-Synonyms")
            
            synonym_list = []
            if synonym_section:
                # Mencari semua elemen <li> di dalam bagian sinonim
                li_elements = synonym_section.find_all('li')
                
                # Menyimpan setiap sinonim
                for li in li_elements:
                    synonym = li.text.strip().lower()
                    if synonym:
                        synonym_list.append(synonym)
                
                print(f"  - Ditemukan {len(synonym_list)} sinonim.")
            else:
                print("  - ⚠️ Bagian 'Depositor-Supplied-Synonyms' tidak ditemukan.")
            
            # 6. Menyimpan data ke file CSV
            if main_key != "Nama Tidak Ditemukan" and synonym_list:
                for synonym in synonym_list:
                    # Format: kata kunci, synonim
                    csv_writer.writerow([main_key, synonym])
                print("  - Data berhasil ditulis ke CSV.")
            elif main_key == "Nama Tidak Ditemukan":
                print("  - ⚠️ Kunci Utama tidak ditemukan, tidak ada data yang ditulis.")
            else:
                print("  - Tidak ada sinonim yang ditemukan, tidak ada data yang ditulis.")


    print("\n✅ Proses scraping SELESAI. Data disimpan dalam **synonym.csv**.")
    # Menutup WebDriver
    driver.quit()

if __name__ == "__main__":
    scrape_pubchem()