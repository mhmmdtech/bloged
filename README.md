# Blogged

Blogged is a modern and powerful blog system developed using React, Inertia.js, Laravel, and Tailwind CSS. It offers a seamless and efficient blogging experience with a combination of cutting-edge frontend technologies and a robust backend framework.

With Blogged, you can create and manage your blog effortlessly. The project leverages the flexibility and interactivity of React to provide a dynamic and responsive user interface. Inertia.js allows for a single-page application (SPA) experience while utilizing Laravel's backend capabilities for efficient data handling and server-side rendering.

## Table of Contents

- [Installation](#installation)
- [Features](#features)
- [Database Documentation](#database-documentation)

## Installation
1. Clone the repository:
```git clone  https://github.com/mhmmdtech/bloged.git```

2. Navigate to the Project Directory:
```cd bloged```

3. Install back-end dependencies:
```composer install```


4. Create the .env file:
```cp .env.example .env```

5. Generate a unique application key:
```php artisan key:generate```

6. Create and migrate the database:
``` php artisan migrate --seed ```

7. Run the server:  
``` php artisan serve ```

8. Install front-end dependencies:
``` npm install ```

9. For development purposes:
``` npm run dev ```

10 For production purposes:  
``` npm run build ```



## Features

Blogged offers a wide range of features to enhance the blogging experience such as:

- Rich Registration Form: The registration form in Blogged provides a comprehensive set of fields to collect user information.
- Captcha Code: To prevent automated spam registrations, Blogged includes a captcha code feature. This helps verify that the user registering is a human and adds an extra layer of security.
- User Analytics by Provinces: Blogged provides an analytics feature that allows administrators to gain valuable insights into the distribution of users across different provinces. This feature provides a clear understanding of which provinces have the highest concentration of users.
- Users Advanced Search: Blogged includes an advanced search functionality that enables administrators to search and filter users based on various criteria.
- User Reports by Province, Cities, and Genders: Administrators can generate user reports in Blogged, providing insights into user demographics based on provinces, cities, and genders. This information helps tailor content and marketing efforts to specific user segments, resulting in more targeted and effective strategies.
- Creating Categories: Blogged allows administrators to create and manage categories for organizing blog posts.
- Post Publishing: Blogged provides a straightforward post publishing feature, allowing bloggers to create, edit, and publish their blog posts easily.
- Avatar Resizing: Blogged includes an avatar resizing feature that automatically adjusts the size of user avatars to ensure consistency and optimal display throughout the blog.
- Storing Thumbnail Posts and Categories in Different Qualities: To optimize performance and improve load times, Blogged stores thumbnail images for posts and categories in different qualities.
- SEO-Friendly URL: Blogged generates SEO-friendly URLs for blog posts, adhering to best practices for search engine optimization. This helps improve the visibility and discoverability of blog content, making it easier for search engines and users to find and access the posts.
- Activity Logs of Users Table: Blogged keeps track of user activity by maintaining activity logs of the users table. This feature allows administrators to monitor and review user actions such as logins, updates to user profiles, and other relevant activities.



## Database Documentation

- [Database Documentation](https://www.notion.so/mhmmdtech/2f33fa408e3e48609bc2fe50b7779861): This link provides detailed documentation about the database structure, including tables, relationships, and any other relevant information. It offers insights into how the data is organized and accessed within the project.
- [Database Schema Design](https://drive.google.com/file/d/1HPqS2bFBT8SKWDBh7qFDQDDsa-g6RVI5/view?usp=sharing): This link visualizes the database schema design using Draw.io, a powerful diagramming tool. It showcases the entities, attributes, and relationships in a graphical representation, helping you understand the overall structure and interconnections of the data model.

Thank you for reviewing the project! We value your expertise and would greatly appreciate any suggestions or feedback you have. Please feel free to contact us through your preferred communication channel or email me(mhmmdtech@yahoo.com) to share your thoughts. We are excited to hear your suggestions and improve our project based on your input.