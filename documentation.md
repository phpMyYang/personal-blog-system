🧭 Dokumentasyon: Personal Blog System

1. Pangkalahatang-ideya (Overview)

Ang Personal Blog System ay isang web application na binuo gamit ang Laravel 12 at Bootstrap 5. Ang layunin nito ay magbigay-daan sa isang rehistradong user (ang may-ari/admin) na magsulat, mamahala, at mag-publish ng mga blog post. Ang mga pampublikong bisita ay maaaring magbasa ng mga nai-publish na post, i-filter ang mga ito base sa kategorya, at mag-iwan ng komento sa pamamagitan ng isang verification system.

2. Teknolohiyang Ginamit (Technology Stack)

**Backend:** Laravel 12, PHP 8.2
**Frontend:** Bootstrap 5, Bootstrap Icons
**Database:** MySQL
**JavaScript Libraries:**
**jQuery:** Ginamit bilang dependency para sa DataTables.
**DataTables.js:** Ginamit para sa advanced, searchable, at paginated tables sa dashboard.
**TinyMCE 7:** Ginamit bilang "What You See Is What You Get" (WYSIWYG) Rich Text Editor para sa paggawa ng post.
**Mga Serbisyo (Services):**
**PHPMailer:** Ginamit bilang custom mail service para sa lahat ng papalabas na email (verification at password reset).
**Google reCAPTCHA v2:** Ginamit para sa proteksyon laban sa spam sa lahat ng public forms (Login, Register, Forgot Password).

3. Mga Pangunahing Tampok (Core Features)

Ang system ay nahahati sa tatlong pangunahing bahagi: Ang Public Area, ang Authentication System, at ang User Dashboard.

### 3.1. Mga Pampublikong Pahina (Public Pages)

Ito ang mga pahinang nakikita ng lahat ng bisita.

**Public Navbar:**
Naglalaman ng "Personal Blog" logo at isang "sticky footer".
**Dynamic Links:**
**Para sa Guests:** Nagpapakita ng **icons** para sa Home, Login, at Register links.
**Para sa Logged-in User:** Nagpapakita ng **Avatar** na may dropdown menu para sa "Dashboard" at "Logout".
**Homepage (Carousel & Grid View):**
**Search:** May pangunahing search bar para i-filter ang mga post base sa title o content.
**Category Filter:** Nagpapakita ng mga "Category Pills" (hal. "Technology", "Travel") para i-filter ang mga post.
**Conditional Layout:**
**Default View (Walang Filter):** Nagpapakita ng isang **Bootstrap Carousel** ng lahat ng published posts. Ito ay awtomatikong lumilipat (auto-slides) bawat 7 segundo, napo-pause sa mouse hover, at may "dot" indicators na naka-fixed sa ibabaw ng footer.
**Filtered View (May Search o Category):** Nagbabago ang layout at nagpapakita ng isang **Grid ng mga resulta** para sa mas madaling pag-browse.
**Single Post View:**
Naa-access kapag pinindot ang "Read More" button.
Nagpapakita ng buong titulo, featured image, at ang buong nilalaman ng post.
\*HTML Rendering:** Ligtas na ipinapakita ang HTML (bold, lists, atbp.) na ginawa sa TinyMCE editor.
Nagpapakita ng pangalan ng may-akda at petsa ng pagkakalathala.
**Error Safe:** Gumagamit ng `?->` (Nullsafe Operator) upang maiwasan ang "white screen" error kung sakaling ang user/author ay na-delete.
**Security:\*\* Kung ang post ay isang draft, ito ay magpapakita ng "404 Not Found".

### 3.2. Sistema ng Komento (sa Single Post Page)

**Comment Form:**
**Para sa Guests:** Kailangang ilagay ang Pangalan at Email.
**Para sa Logged-in Users:** Awtomatikong kinukuha ang pangalan; kailangan na lang ilagay ang komento.
**Guest Comment Verification:**
Ang mga komento ng bisita ay ise-save bilang `is_verified = false` (Pending).
Isang verification email (gamit ang PHPMailer) ang ipapadala sa guest. Ang user ay makakatanggap ng **Toast notification** na nagsasabing "Please check your email".
Ang komento ay **hindi lilitaw sa publiko** hangga't hindi kini-click ng guest ang link sa email (o manu-manong i-approve ng admin).
**User Comment Approval:**
Ang mga komento mula sa naka-log in na user (ang admin) ay awtomatikong `is_verified = true` at lumalabas agad.

### 3.3. Sistema ng Awtorisasyon (Authentication System)

**Pangkalahatang Disenyo:**
Gumagamit ng custom, modernong two-sided layout (Illustration sa kaliwa, Form sa kanan).
Gumagamit ng custom color palette (`#D97D55`, `#F4E9D7`, atbp.).
**Alert System:** Lahat ng system messages (Success, Error, at Validation) ay ipinapakita bilang **auto-hiding Toast (modal) notifications** para sa mas malinis na UX.
**Registration:**
Form para sa Name, Email, at Password (na may "Show Password" icon).
**Terms & Privacy:** May "Agree to Terms" checkbox. Ang pag-click sa "Terms of Use" o "Privacy Policy" ay magbubukas ng isang **Bootstrap Modal**.
**Proseso:** Nagpapadala ng verification email (gamit ang PHPMailer). Ang user ay ididirekta sa isang "Check your email" advisory page (`verify.blade.php`) na may "Resend Email" button.
**Login:**
Form para sa Email at Password (na may "Show Password" icon).
May "Remember Me" functionality.
**Proseso:** Pinipigilan ang pag-login kung ang email ay hindi pa verified. Ang mga error ay ipinapakita bilang Toast.
**Password Reset:**
Buong 3-step flow: "Forgot Password" form , email link (via PHPMailer) , at "Reset Password" form.
**Error Handling:** Hindi na gumagamit ng generic success message. Ngayon ay nagpapakita na ng specific **Toast Error** kung ang email ay "Account not found or email address is not verified."

### 3.4. User Dashboard (Ang "Control Panel")

Ang pribadong lugar para sa may-ari ng blog, protektado ng `auth` at `verified` middleware.

**Dashboard Navbar:**
_ Naglalaman ng **Avatar** ng user bilang dropdown (40px).
_ Ang dropdown menu ay naglalaman ng User Info (Avatar, Name, Email), "My Profile" link, at "Logout" button.
_ Ang "Active" state ng mga link (Dashboard, Create Post, Categories, Comments) ay dynamic.
**Dashboard Home (Post List):**
Nagpapakita ng "Welcome" message at mga dynamic na **Stat Cards** (Total Posts, Published, Drafts).
**DataTables.js:** Ang pangunahing listahan ng post ay gumagamit ng DataTables para sa Search, Pagination ("Show [10] entries"), at Sorting.
**Custom Styling:** Inayos ang CSS para ang "Show entries" at "Search" ay nasa iisang malinis na row.
**Column Layout:** Ang "Title" column ay awtomatikong nag-te-**text truncate** (nagiging "...") kapag masyadong mahaba.
**Action Buttons:** Mga Icon buttons (View, Edit, Delete) na may tooltips
**Create/Edit Post:**
**Content:** Ang `<textarea>` ay pinalitan ng **TinyMCE 7 Rich Text Editor**.
**Image Upload:** Pinalitan ng isang custom **Drag-and-Drop** file uploader na may file name indicator.
**Categories:** Isang scrollable checkbox list ang nagpapahintulot sa pag-assign ng isa o higit pang kategorya sa post.
**Save Logic:** May kasamang JS fix (`tinymce.triggerSave()`) para siguradong nase-save ang content mula sa editor.
**Profile Management:**
Isang dedikadong "Account Management" page na naa-access mula sa avatar dropdown.
**Three-Card Layout:** 1. **Personal Information:** Para sa pag-update ng Avatar (na may preview at "Remove" option), Name, at Email. 2. **Change Password:** Form para sa Current Password at New Password. 3. **Delete Account:** Isang "Danger Zone" card na may `confirm()` dialog para sa permanenteng pagbura ng account.
**Category Management:**
_ Isang page para sa CRUD (Create, Read, Update, Delete) ng mga kategorya.
_ Gumagamit ng split-screen layout: Create form sa kaliwa, DataTables list ng categories sa kanan.
**Comment Management:**
_ Isang page na naglilista ng _lahat_ ng komento (Pending at Verified) gamit ang DataTables. \* Nagpapahintulot sa admin na manu-manong i-**Approve** (para maging public) o i-**Delete** ang mga komento ng guest.
