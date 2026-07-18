export const SECTION_TYPES = [
  {
    type: 'hero',
    title: 'Hero Banner',
    icon: 'tabler-photo',
    color: 'primary',
    defaultContent: {
      heading: 'Welcome to Our Store',
      subheading: 'Discover amazing products at great prices',
      buttonText: 'Shop Now',
      buttonLink: '#products',
      backgroundImage: '',
      alignment: 'center',
    },
    defaultSettings: {
      bgColor: '',
      textColor: '#ffffff',
      paddingTop: 80,
      paddingBottom: 80,
      overlayOpacity: 40,
    },
  },
  {
    type: 'products',
    title: 'Products Grid',
    icon: 'tabler-grid-3x3',
    color: 'success',
    defaultContent: {
      heading: 'Featured Products',
      source: 'all',
      columns: 3,
    },
    defaultSettings: {
      bgColor: '',
      paddingTop: 40,
      paddingBottom: 40,
    },
  },
  {
    type: 'banner',
    title: 'Image Banner',
    icon: 'tabler-layout-banner',
    color: 'info',
    defaultContent: {
      heading: 'Special Offer',
      description: 'Get up to 50% off on selected items',
      image: '',
      buttonText: 'View Details',
      buttonLink: '#',
      overlayPosition: 'left',
    },
    defaultSettings: {
      bgColor: '',
      textColor: '#ffffff',
      height: 400,
      paddingTop: 0,
      paddingBottom: 0,
    },
  },
  {
    type: 'text',
    title: 'Text Block',
    icon: 'tabler-file-text',
    color: 'warning',
    defaultContent: {
      heading: 'About Us',
      body: 'Write your content here. You can add paragraphs, lists, and more.',
      alignment: 'left',
    },
    defaultSettings: {
      bgColor: '',
      textColor: '',
      maxWidth: 800,
      paddingTop: 40,
      paddingBottom: 40,
    },
  },
  {
    type: 'gallery',
    title: 'Gallery',
    icon: 'tabler-photo-share',
    color: 'purple',
    defaultContent: {
      heading: 'Our Gallery',
      images: [],
      columns: 3,
    },
    defaultSettings: {
      bgColor: '',
      gap: 10,
      borderRadius: 8,
      paddingTop: 40,
      paddingBottom: 40,
    },
  },
  {
    type: 'features',
    title: 'Features',
    icon: 'tabler-star',
    color: 'orange',
    defaultContent: {
      heading: 'Why Choose Us',
      items: [
        { icon: 'tabler-truck', title: 'Free Shipping', description: 'On orders over $50' },
        { icon: 'tabler-shield-check', title: 'Secure Payment', description: '100% secure checkout' },
        { icon: 'tabler-headset', title: '24/7 Support', description: 'Dedicated support team' },
        { icon: 'tabler-refresh', title: 'Easy Returns', description: '30-day return policy' },
      ],
    },
    defaultSettings: {
      bgColor: '',
      textColor: '',
      columns: 4,
      paddingTop: 40,
      paddingBottom: 40,
    },
  },
  {
    type: 'testimonials',
    title: 'Testimonials',
    icon: 'tabler-message-star',
    color: 'teal',
    defaultContent: {
      heading: 'What Our Customers Say',
      items: [
        { name: 'Ahmed Ali', role: 'Customer', content: 'Amazing quality products and fast delivery!', rating: 5 },
        { name: 'Sara Mohamed', role: 'Customer', content: 'Best online shopping experience I have ever had.', rating: 5 },
        { name: 'Omar Hassan', role: 'Customer', content: 'Great prices and excellent customer service.', rating: 4 },
      ],
    },
    defaultSettings: {
      bgColor: '',
      textColor: '',
      columns: 3,
      paddingTop: 40,
      paddingBottom: 40,
    },
  },
  {
    type: 'cta',
    title: 'Call to Action',
    icon: 'tabler-speakerphone',
    color: 'red',
    defaultContent: {
      heading: 'Ready to Start Shopping?',
      description: 'Browse our collection and find exactly what you need.',
      buttonText: 'Shop Now',
      buttonLink: '#products',
    },
    defaultSettings: {
      bgColor: '',
      textColor: '#ffffff',
      paddingTop: 60,
      paddingBottom: 60,
    },
  },
  {
    type: 'contact',
    title: 'Contact Info',
    icon: 'tabler-mail',
    color: 'cyan',
    defaultContent: {
      heading: 'Get in Touch',
      phone: '',
      email: '',
      address: '',
      workingHours: 'Mon - Fri: 9:00 AM - 6:00 PM',
    },
    defaultSettings: {
      bgColor: '',
      textColor: '',
      columns: 3,
      paddingTop: 40,
      paddingBottom: 40,
    },
  },
  {
    type: 'divider',
    title: 'Divider',
    icon: 'tabler-divider',
    color: 'secondary',
    defaultContent: {
      style: 'line',
    },
    defaultSettings: {
      color: '#e0e0e0',
      thickness: 1,
      width: 100,
      paddingTop: 20,
      paddingBottom: 20,
    },
  },
]

export function getSectionConfig(type) {
  return SECTION_TYPES.find(s => s.type === type)
}

export function getDefaultContent(type) {
  const config = getSectionConfig(type)
  return config ? JSON.parse(JSON.stringify(config.defaultContent)) : {}
}

export function getDefaultSettings(type) {
  const config = getSectionConfig(type)
  return config ? JSON.parse(JSON.stringify(config.defaultSettings)) : {}
}
