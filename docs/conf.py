import sys, os

extensions = []
templates_path = ['.templates']
source_suffix = '.rst'
master_doc = 'index'
project = u'Symfony REST Edition Distribution'
copyright = u'2013, Ingewikkeld'
version = '1.0'
release = '1.0'
exclude_patterns = ['.build']
pygments_style = 'friendly'

# -- Options for HTML output ---------------------------------------------------

html_theme = 'nature'
#html_title = None
#html_logo = None
html_static_path = ['.static']
htmlhelp_basename = 'sred'

# -- Options for LaTeX output --------------------------------------------------

latex_elements = {
    'papersize': 'a4paper',
    'pointsize': '10pt',
}

latex_documents = [
  ('index', 'sred.tex', u'Symfony REST Edition Distribution', u'Ingewikkeld', 'manual'),
]

#latex_logo = None

# -- Options for manual page output --------------------------------------------

man_pages = [
    ('index', 'sred', u'Symfony REST Edition Distribution', [u'Ingewikkeld'], 1)
]

# -- Options for Texinfo output ------------------------------------------------

texinfo_documents = [
  ('index', 'sred', u'Symfony REST Edition Distribution', u'Ingewikkeld', 'sred', 'One line description of project.', 'Miscellaneous'),
]
