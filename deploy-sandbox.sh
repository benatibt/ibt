#!/bin/bash
set -e

echo "Pushing theme to Hostinger..."
git subtree push --prefix=theme/islands-book-trust-theme hostinger-theme main

echo "Pushing plugin to Hostinger..."
git subtree push --prefix=plugin/ibt_customisation hostinger-plugin main

echo "✅ Sandbox updated (theme + plugin)."
