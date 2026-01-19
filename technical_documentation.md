# Dokumentacioni Teknik - Jewelry Shop

Ky dokument përmban detajet teknike, arkitekturën dhe specifikimet e sistemit të e-commerce për dyqanin e bizhuterive.

---

## 1. Përshkrimi Narrativ i Problemit

Menaxhimi i një dyqani bizhuterish përfshin gjurmimin e produkteve me vlerë të lartë, kategorizimin e tyre sipas materialeve (ari, argjend), gurëve të çmuar dhe karateve, si dhe menaxhimin e porosive të klientëve në mënyrë të sigurt. 
Problemi kryesor është nevoja për:
- Një platformë digjitale ku klientët mund të shfletojnë koleksionet.
- Një sistem automatik për menaxhimin e inventarit (stock management).
- Siguri e lartë për transaksionet dhe të dhënat e përdoruesve.
- Automatizimi i procesit të porosisë prej shportës deri te dërgimi.

Sistemi zgjidh këto probleme duke ofruar një ndërfaqe web moderne, një bazë të dhënash të optimizuar dhe nivele të qarta aksesi për Administratorët, Magazinierët dhe Klientët.

---

## 2. Diagrama e Kontekstit

Sistemi "Jewelry Shop" ndërvepron me tre aktorë kryesorë dhe është i ndarë në module funksionale.


graph TD
    subgraph "Sistemi Jewelry Shop"
        M1[Moduli i Produkteve]
        M2[Moduli i Shportës dhe Porosive]
        M3[Moduli i Autentikimit]
        M4[Moduli i Pagesave]
        M5[Moduli i Inventarit]
    end

    Klient((Klient)) -- "Shfleton, Porosit, Paguan" --> M1
    Klient -- "Menaxhon Llogarinë" --> M3
    
    Admin((Administrator)) -- "Menaxhon Produkte, Sheh Raporte" --> M1
    Admin -- "Menaxhon Përdoruesit" --> M3

    Warehouse((Magazinier)) -- "Gjurmon Dërgesat, Përditëson Stokun" --> M5
    
    M2 -- "Krijon Faturë" --> M4
    M4 -- "Përditëson Statusin" --> M2
```

---

## 3. Diagramat Use Case

### Proceset Kryesore: Porosia, Pagesa, Transporti

```mermaid
useCaseDiagram
    actor Klient
    actor Admin
    actor Magazinier

    package "Procesi i Porosisë" {
        Klient --> (Shton në Shportë)
        Klient --> (Kryen Pagesën)
        (Kryen Pagesën) ..> (Zgjedh Adresën) : include
    }

    package "Menaxhimi i Porosive" {
        Admin --> (Aprovon Porosinë)
        Magazinier --> (Përgatit Dërgesën)
        Magazinier --> (Gjeneron Tracking Number)
    }

    package "Inventari dhe Kategoritë" {
        Admin --> (Shton Produkt të Ri)
        Admin --> (Përditëson / Fshin Produkt)
        Admin --> (Menaxhon Kategoritë)
        (Shton Produkt të Ri) ..> (Cakton Kategorinë) : include
    }
```

---

## 4. Skema Logjike e Bazës së të Dhënave

Baza e të dhënave pasqyron marrëdhëniet midis entiteteve kryesore.

### Struktura e Tabelave:

1.  **users**: Ruan të dhënat e përdoruesve (Klient, Admin, Magazinier).
2.  **categories & material**: Klasifikimi i produkteve.
3.  **products**: Detajet bazë të bizhuterisë (emri, përshkrimi, çmimi, stoku).
4.  **jewelry_details**: Detaje specifike si pesha, karati dhe lloji i gurit (Relacion 1:1 me products).
5.  **cart & cart_items**: Menaxhimi i përkohshëm i artikujve para blerjes.
6.  **orders & order_items**: Të dhëhat historike të porosive të kryera.
7.  **payments**: Regjistrimi i transaksioneve (PayPal, Credit Card, etj.).
8.  **shipments**: Gjurmimi i dërgesave nga magazinierët.
9.  **stock_logs**: Logimi i çdo lëvizjeje të stokut (Rizgjidhje, Shitje, Kthim).

---

## 5. Pageflow / Sitemap

Rrjedha e faqeve për përdoruesin:

1.  **Public/Home**: Ballina me produkte të zgjedhura.
2.  **Products Catalog**: Listimi i të gjitha bizhuterive me filtra.
3.  **Product Details**: Detajet teknike, fotot dhe butoni "Add to Cart".
4.  **Cart**: Rishikimi i artikujve.
5.  **Checkout**: Zgjedhja e adresës dhe metodës së pagesës.
6.  **Success Page**: Konfirmimi i porosisë.
7.  **Auth (Login/Register)**: Hyrja dhe regjistrimi.
8.  **Admin Dashboard**: Paneli kryesor i menaxhimit.
9.  **Product Management**: Listimi, editimi dhe fshirja e produkteve.
10. **Category Management**: Krijimi dhe menaxhimi i kategorive të produkteve.

---

## 6. Skicë e Layout-it (Wireframe)

### Layout-i i Faqes Kryesore (Public Side)
```text
+-------------------------------------------------------------+
| [LOGO]        [Search...]       [Cart(0)]  [Login/Account] | Navbar
+-------------------------------------------------------------+
|                                                             |
|   [ Banner: Koleksioni i Ri i Diamanteve ]                  | Hero Section
|                                                             |
+-------------------------------------------------------------+
|  [ Kategori: Unaza ] [ Kategori: Varëse ] [ Kategori: Orë ] | Categories
+-------------------------------------------------------------+
|                                                             |
|  [FOTO] Product 1    [FOTO] Product 2    [FOTO] Product 3   | Product Grid
|  Price: 150€         Price: 300€         Price: 220€        |
|  [Add to Cart]       [Add to Cart]       [Add to Cart]      |
|                                                             |
+-------------------------------------------------------------+
| [Rreth Nesh]  [Kontakt]  [Social Media]  [Termat & Kushtet] | Footer
+-------------------------------------------------------------+
```

### Layout-i i Dashboard-it Admin
```text
+-------------------------------------------------------------+
| [Admin Panel]         [Welcome, Admin]           [Logout]   | Header
+-------------------------------------------------------------+
| SIDEBAR      |                                              |
| - Dashboard  |  [ Statistika: Shitjet Sot | Porositë Reja ] |
| - Produktet  |                                              |
| - Kategoritë |  [ Listimi i Porosive të Fundit (Tabelë) ]   | Main Content
| - Porositë   |  [ ID | Klienti | Totali | Statusi | Veprime]|
| - Përdoruesit|                                              |
+--------------+----------------------------------------------+
```

---

## 7. Strategjia e Sigurisë

Mbrojtja e sistemit bazohet në disa shtresa:

1.  **Mbrojtja e Fjalëkalimeve**: Përdorimi i funksionit `password_hash()` të PHP (BCRYPT) për të ruajtur fjalëkalimet në mënyrë të enkriptuar.
2.  **Kontrolli i Aksesit (RBAC)**: Çdo faqe verifikon rolin e përdoruesit (`role` në tabelën `users`) para se të lejojë aksesin (psh. vetëm 'Admin' mund të hyjë te `/admin/`).
3.  **SQL Injection**: Të gjitha pyetjet (queries) në DB kryhen përmes PDO me Prepared Statements.
4.  **Mbrojtja nga Brute Force**: Fusha `failed_attempts` dhe `block_date` në tabelën `users` përdoren për të bllokuar llogaritë pas disa tentativave të dështuara.
5.  **Validimi i të Dhënave**: Kontrolli i formateve (email, numra) në anën e Serverit dhe Klientit.
6.  **CSRF & XSS**: Përdorimi i filtrimit të inputeve dhe (rekomandohet) implementimi i tokenave CSRF në forma.
